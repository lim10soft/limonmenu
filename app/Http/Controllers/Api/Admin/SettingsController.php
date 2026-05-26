<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'slug'           => 'required|string|max:50|regex:/^[a-z0-9\-]+$/',
            'logo'           => 'nullable|url',
            'theme_color'    => 'required|string|max:20',
            'nexopos_url'    => 'nullable|url',
            'nexopos_token'  => 'nullable|string',
            'tables_enabled'  => 'boolean',
            'orders_enabled'  => 'boolean',
            'currency_code'   => 'nullable|string|max:10',
            'banner_image'    => 'nullable|url',
            'instagram_url'   => 'nullable|url',
            'primary_lang'    => 'nullable|string|max:10',
        ]);

        $tenant = $request->user()->tenant;

        if ($data['slug'] !== $tenant->slug) {
            $exists = \App\Models\Tenant::where('slug', $data['slug'])
                ->where('id', '!=', $tenant->id)
                ->exists();

            if ($exists) {
                return response()->json(['message' => 'Bu slug zaten kullanımda.'], 422);
            }
        }

        $tenant->update($data);

        // Ensure primary_lang is always active in tenant_languages
        if (! empty($data['primary_lang'])) {
            \App\Models\TenantLanguage::updateOrCreate(
                ['tenant_id' => $tenant->id, 'lang_code' => $data['primary_lang']],
                ['active' => true]
            );
        }

        return response()->json(['tenant' => $tenant]);
    }

    public function syncNexopos(Request $request)
    {
        $tenant = $request->user()->tenant;

        $missing = [];
        if (! $tenant->nexopos_url)   $missing[] = 'NexoPOS URL';
        if (! $tenant->nexopos_token) $missing[] = 'NexoPOS Token';

        if ($missing) {
            return response()->json([
                'message' => implode(' ve ', $missing) . ' eksik. qrmenu Ayarlar sayfasından doldurun.'
            ], 422);
        }

        $base = rtrim($tenant->nexopos_url, '/');

        $response = Http::withToken($tenant->nexopos_token)
            ->get($base . '/api/products');

        if (! $response->successful()) {
            return response()->json([
                'message' => 'NexoPOS\'a bağlanılamadı. HTTP ' . $response->status()
            ], 502);
        }

        $body = $response->json();

        // Hem düz array hem {data:[]} formatını destekle
        if (isset($body['data'])) {
            $nexoProducts = $body['data'];
        } elseif (is_array($body) && isset($body[0])) {
            $nexoProducts = $body;
        } else {
            $nexoProducts = [];
        }

        if (empty($nexoProducts)) {
            return response()->json(['message' => 'NexoPOS\'tan ürün gelmedi.', 'synced' => 0], 422);
        }

        // Kategori adı için ayrı istek at
        $categoryMap = [];
        $catResponse = Http::withToken($tenant->nexopos_token)->get($base . '/api/categories');
        if ($catResponse->successful()) {
            $cats = $catResponse->json();
            $catList = isset($cats['data']) ? $cats['data'] : (is_array($cats) ? $cats : []);
            foreach ($catList as $cat) {
                if (isset($cat['id'])) {
                    $categoryMap[$cat['id']] = $cat['name'] ?? 'Genel';
                }
            }
        }

        $synced = 0;

        foreach ($nexoProducts as $np) {
            if (empty($np['id'])) continue;

            // Kategori adını çöz
            $catName = $np['category']['name']
                ?? $categoryMap[$np['category_id'] ?? 0]
                ?? 'Genel';

            // Fiyatı çöz: önce sale_price, sonra unit_quantities içinden
            $price = $np['sale_price'] ?? 0;
            if (! $price && ! empty($np['unit_quantities'])) {
                $price = $np['unit_quantities'][0]['sale_price'] ?? 0;
            }

            $category = Category::withoutGlobalScopes()->firstOrCreate(
                ['tenant_id' => $tenant->id, 'name' => $catName],
                ['active' => true, 'sort_order' => 0]
            );

            Product::withoutGlobalScopes()->updateOrCreate(
                ['tenant_id' => $tenant->id, 'nexopos_id' => $np['id']],
                [
                    'category_id' => $category->id,
                    'name'        => $np['name'],
                    'description' => $np['description'] ?? null,
                    'price'       => $price,
                    'image'       => $np['thumbnail'] ?? null,
                    'active'      => true,
                ]
            );

            $synced++;
        }

        return response()->json(['synced' => $synced]);
    }
}
