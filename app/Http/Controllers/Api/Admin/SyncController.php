<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SyncController extends Controller
{
    // yeniolcak'tan gelen ürün+kategori verisini alır, kaydeder
    public function receive(Request $request)
    {
        $tenant   = $request->user()->tenant;
        $products = $request->input('products', []);
        $synced   = 0;

        if (empty($products)) {
            return response()->json(['message' => 'Gönderilen ürün verisi boş.', 'synced' => 0], 422);
        }

        foreach ($products as $p) {
            if (empty($p['id'])) continue;

            $catNexoposId = isset($p['category_id']) ? (int) $p['category_id'] : null;
            $catName      = $p['category_name'] ?? 'Genel';

            // Önce nexopos_id ile bul, yoksa isim ile oluştur
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

                // İsimle bulunan kaydın nexopos_id'si yoksa güncelle
                if ($catNexoposId && ! $category->nexopos_id) {
                    $category->update(['nexopos_id' => $catNexoposId]);
                }
            }

            $product = Product::withoutGlobalScopes()->updateOrCreate(
                ['tenant_id' => $tenant->id, 'nexopos_id' => $p['id']],
                [
                    'category_id' => $category->id,
                    'name'        => $p['name'],
                    'description' => $p['description'] ?? null,
                    'price'       => $p['sale_price'] ?? 0,
                    'image'       => $p['image'] ?? null,
                    'active'      => isset($p['visible']) ? (bool) $p['visible'] : true,
                    'in_stock'    => ($p['status'] ?? 'available') === 'available' ? 1 : 0,
                ]
            );

            // Birimleri güncelle
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

            $synced++;
        }

        return response()->json(['synced' => $synced]);
    }

    public function uploadImage(Request $request, $nexoposId)
    {
        $request->validate(['image' => 'required|image|max:5120']);

        $tenant = $request->user()->tenant;

        $file     = $request->file('image');
        $filename = 'products/' . $tenant->id . '/' . uniqid() . '.' . $file->getClientOriginalExtension();

        Storage::disk('public')->putFileAs(
            'products/' . $tenant->id,
            $file,
            basename($filename)
        );

        $url = Storage::disk('public')->url($filename);

        $product = Product::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('nexopos_id', $nexoposId)
            ->first();

        if ($product) {
            $product->update(['image' => $url]);
        }

        return response()->json(['success' => true, 'url' => $url]);
    }
}
