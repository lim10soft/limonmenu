<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Tenant;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function nexoposProduct(Request $request)
    {
        $token = $request->bearerToken();
        if (! $token) {
            return response()->json(['message' => 'Yetkisiz.'], 401);
        }

        $tenant = Tenant::where('nexopos_token', $token)->first();
        if (! $tenant) {
            return response()->json(['message' => 'Geçersiz token.'], 401);
        }

        $p = $request->input('product');
        if (empty($p) || empty($p['id'])) {
            return response()->json(['message' => 'Ürün verisi eksik.'], 422);
        }

        $catNexoposId = isset($p['category_id']) ? (int) $p['category_id'] : null;
        $catName      = $p['category_name'] ?? 'Genel';

        $category = null;
        if ($catNexoposId) {
            $category = Category::withoutGlobalScopes()
                ->where('tenant_id', $tenant->id)
                ->where('nexopos_id', $catNexoposId)
                ->first();
        }

        if (! $category) {
            $category = Category::withoutGlobalScopes()->firstOrCreate(
                ['tenant_id' => $tenant->id, 'name' => $catName],
                ['nexopos_id' => $catNexoposId, 'active' => true, 'sort_order' => 0]
            );

            if ($catNexoposId && ! $category->nexopos_id) {
                $category->update(['nexopos_id' => $catNexoposId]);
            }
        }

        $product = Product::withoutGlobalScopes()->updateOrCreate(
            ['tenant_id' => $tenant->id, 'nexopos_id' => (int) $p['id']],
            [
                'category_id' => $category->id,
                'name'        => $p['name'],
                'description' => $p['description'] ?? null,
                'price'       => (float) ($p['sale_price'] ?? 0),
                'image'       => $p['image'] ?? null,
                'active'      => isset($p['visible']) ? (bool) $p['visible'] : true,
                'in_stock'    => ($p['status'] ?? 'available') === 'available' ? 1 : 0,
                'sort_order'  => isset($p['sort_order']) ? (int) $p['sort_order'] : 0,
            ]
        );

        if (! empty($p['units']) && is_array($p['units'])) {
            ProductUnit::where('product_id', $product->id)->delete();
            foreach ($p['units'] as $i => $unit) {
                ProductUnit::create([
                    'product_id' => $product->id,
                    'label'      => $unit['label'] ?? '',
                    'price'      => (float) ($unit['price'] ?? 0),
                    'sort_order' => $i,
                ]);
            }
        }

        return response()->json([
            'success'    => true,
            'product_id' => $product->id,
            'nexopos_id' => (int) $p['id'],
        ]);
    }
}
