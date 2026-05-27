<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\TenantLanguage;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TranslateController extends Controller
{
    // MyMemory lang code overrides
    private const LANG_MAP = ['zh' => 'zh-CN'];

    public function autoTranslateAll(Request $request)
    {
        @set_time_limit(300);

        $tenant      = $request->user()->tenant;
        $primaryLang = $tenant->primary_lang ?? 'tr';
        $userEmail   = $request->user()->email ?? 'translate@qrmenu.app';

        $targetLangs = TenantLanguage::where('tenant_id', $tenant->id)
            ->where('active', true)
            ->where('lang_code', '!=', $primaryLang)
            ->pluck('lang_code')
            ->toArray();

        if (empty($targetLangs)) {
            return response()->json(['message' => 'Çevrilecek aktif dil yok.', 'count' => 0, 'langs' => 0]);
        }

        $saved = 0;

        $categories = Category::withoutGlobalScopes()->where('tenant_id', $tenant->id)->with('translations')->get();
        $products   = Product::withoutGlobalScopes()->where('tenant_id', $tenant->id)->with('translations')->get();

        // If primary_lang has no translations yet, fall back to base names and use 'tr' as source
        $hasPrimaryContent = $products->contains(fn($p) => $p->translations->firstWhere('lang_code', $primaryLang) !== null)
            || $categories->contains(fn($c) => $c->translations->firstWhere('lang_code', $primaryLang) !== null);

        if ($hasPrimaryContent) {
            $sourceLang = $primaryLang;
            $catNames   = $categories->map(fn($c) => $c->translations->firstWhere('lang_code', $primaryLang)?->name ?? $c->name ?? '')->toArray();
            $prodNames  = $products->map(fn($p) => $p->translations->firstWhere('lang_code', $primaryLang)?->name ?? $p->name ?? '')->toArray();
            $prodDescs  = $products->map(fn($p) => $p->translations->firstWhere('lang_code', $primaryLang)?->description ?? $p->description ?? '')->toArray();
        } else {
            // No primary lang translations — use base names (assumed Turkish)
            $sourceLang = 'tr';
            $catNames   = $categories->map(fn($c) => $c->name ?? '')->toArray();
            $prodNames  = $products->map(fn($p) => $p->name ?? '')->toArray();
            $prodDescs  = $products->map(fn($p) => $p->description ?? '')->toArray();
        }

        // Recalculate target langs excluding the actual source language
        $targetLangs = TenantLanguage::where('tenant_id', $tenant->id)
            ->where('active', true)
            ->where('lang_code', '!=', $sourceLang)
            ->pluck('lang_code')
            ->toArray();

        if (empty($targetLangs)) {
            return response()->json(['message' => 'Çevrilecek aktif dil yok.', 'count' => 0, 'langs' => 0]);
        }

        // ── Kategoriler ──
        foreach ($targetLangs as $lang) {
            $translated = $this->translateBulk($catNames, $sourceLang, $lang, $userEmail);
            foreach ($categories as $i => $cat) {
                $name       = trim($translated[$i] ?? '');
                $sourceName = trim($catNames[$i] ?? '');
                if (empty($name) || mb_strtolower($name) === mb_strtolower($sourceName)) continue;
                CategoryTranslation::updateOrCreate(
                    ['category_id' => $cat->id, 'lang_code' => $lang],
                    ['name' => $name]
                );
                $saved++;
            }
        }

        // ── Ürünler ──
        foreach ($targetLangs as $lang) {
            $names = $this->translateBulk($prodNames, $sourceLang, $lang, $userEmail);
            $descs = $this->translateBulk($prodDescs, $sourceLang, $lang, $userEmail);
            foreach ($products as $i => $product) {
                $name       = trim($names[$i] ?? '');
                $sourceName = trim($prodNames[$i] ?? '');
                if (empty($name) || mb_strtolower($name) === mb_strtolower($sourceName)) continue;
                $desc = trim($descs[$i] ?? '');
                $sourceDesc = trim($prodDescs[$i] ?? '');
                ProductTranslation::updateOrCreate(
                    ['product_id' => $product->id, 'lang_code' => $lang],
                    ['name' => $name, 'description' => ($desc && mb_strtolower($desc) !== mb_strtolower($sourceDesc)) ? $desc : null]
                );
                $saved++;
            }
        }

        return response()->json([
            'message' => 'Çeviriler tamamlandı.',
            'count'   => $saved,
            'langs'   => count($targetLangs),
        ]);
    }

    // Kept for backwards compatibility
    public function autoTranslateProducts(Request $request) { return $this->autoTranslateAll($request); }
    public function autoTranslateCategories(Request $request) { return $this->autoTranslateAll($request); }

    private function translateBulk(array $texts, string $from, string $to, string $email): array
    {
        $fromCode = self::LANG_MAP[$from] ?? $from;
        $toCode   = self::LANG_MAP[$to]   ?? $to;

        // Only send non-empty texts; track original indices
        $pending = [];
        foreach ($texts as $i => $text) {
            if (!empty(trim($text))) {
                $pending[$i] = $text;
            }
        }

        if (empty($pending)) {
            return $texts;
        }

        $pendingList = array_values($pending);
        $pendingKeys = array_keys($pending);

        try {
            $responses = Http::pool(function (Pool $pool) use ($pendingList, $fromCode, $toCode, $email) {
                return array_map(
                    fn($text) => $pool->timeout(15)->get('https://api.mymemory.translated.net/get', [
                        'q'        => $text,
                        'langpair' => "{$fromCode}|{$toCode}",
                        'de'       => $email,
                    ]),
                    $pendingList
                );
            });
        } catch (\Exception) {
            return $texts;
        }

        $results = $texts;
        foreach ($responses as $j => $response) {
            $originalIndex = $pendingKeys[$j];
            if (! ($response instanceof \Illuminate\Http\Client\Response)) {
                continue;
            }
            try {
                if ($response->ok()) {
                    $data       = $response->json();
                    $translated = $data['responseData']['translatedText'] ?? null;
                    if ($translated
                        && (int)($data['responseStatus'] ?? 0) === 200
                        && ! str_starts_with((string) $translated, 'MYMEMORY WARNING')
                    ) {
                        $results[$originalIndex] = $translated;
                    }
                }
            } catch (\Exception) {
                // keep original
            }
        }

        return $results;
    }
}
