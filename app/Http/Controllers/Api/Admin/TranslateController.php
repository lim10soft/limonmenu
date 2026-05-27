<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\TenantLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TranslateController extends Controller
{
    // Google Translate uses different codes for some languages
    private const LANG_MAP = ['zh' => 'zh-CN', 'he' => 'iw'];

    public function autoTranslateAll(Request $request)
    {
        @set_time_limit(300);

        $tenant      = $request->user()->tenant;
        $primaryLang = $tenant->primary_lang ?? 'tr';

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
            $translated = $this->translateBulk($catNames, $sourceLang, $lang);
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
            $names = $this->translateBulk($prodNames, $sourceLang, $lang);
            $descs = $this->translateBulk($prodDescs, $sourceLang, $lang);
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

    private function translateBulk(array $texts, string $from, string $to): array
    {
        $fromCode = self::LANG_MAP[$from] ?? $from;
        $toCode   = self::LANG_MAP[$to]   ?? $to;

        // Collect non-empty texts, normalise internal newlines so they don't
        // collide with the \n separator used for batching.
        $pending = [];
        foreach ($texts as $i => $text) {
            $clean = trim(str_replace(["\r\n", "\r", "\n"], ' ', $text));
            if ($clean !== '') {
                $pending[$i] = $clean;
            }
        }

        if (empty($pending)) {
            return $texts;
        }

        $pendingList = array_values($pending);
        $pendingKeys = array_keys($pending);
        $results     = $texts;

        // Send 100 items per request joined by \n — Google preserves line
        // boundaries, so we get one translated line per source line back.
        $batches     = array_chunk($pendingList, 100);
        $batchedKeys = array_chunk($pendingKeys, 100);

        foreach ($batches as $bIdx => $batch) {
            $keys   = $batchedKeys[$bIdx];
            $joined = implode("\n", $batch);

            try {
                $response = Http::timeout(30)
                    ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                    ->get('https://translate.googleapis.com/translate_a/single', [
                        'client' => 'gtx',
                        'sl'     => $fromCode,
                        'tl'     => $toCode,
                        'dt'     => 't',
                        'q'      => $joined,
                    ]);
            } catch (\Exception) {
                continue; // keep originals for this batch
            }

            if (! ($response instanceof \Illuminate\Http\Client\Response) || ! $response->ok()) {
                continue;
            }

            try {
                $data = $response->json();
                // Google returns: [[[chunk, source, ...], ...], null, detected_lang]
                // Concatenate all translated chunks, then split by \n.
                if (is_array($data) && isset($data[0]) && is_array($data[0])) {
                    $parts = array_filter(
                        array_column($data[0], 0),
                        fn($p) => is_string($p)
                    );
                    $translatedAll   = implode('', $parts);
                    $translatedLines = explode("\n", $translatedAll);

                    foreach ($keys as $j => $originalIndex) {
                        $line = trim($translatedLines[$j] ?? '');
                        if ($line !== '') {
                            $results[$originalIndex] = $line;
                        }
                    }
                }
            } catch (\Exception) {
                // keep originals for this batch
            }
        }

        return $results;
    }
}
