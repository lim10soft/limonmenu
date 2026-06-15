<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Department;
use App\Models\DepartmentProductOverride;
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

            // Kategori resmini güncelle
            $catImage = $p['category_image'] ?? null;
            if ($catImage && $category->image !== $catImage) {
                $category->update(['image' => $catImage]);
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
                    'is_featured' => (bool) ($p['is_featured'] ?? false),
                    'sort_order'  => isset($p['sort_order']) ? (int) $p['sort_order'] : 0,
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

            // Departman fiyatları — tenant'ın departmanları sırayla
            $depts = Department::withoutGlobalScopes()
                ->where('tenant_id', $tenant->id)
                ->orderBy('sort_order')
                ->take(2)
                ->get();

            // 1. departman → sale_price
            $salePrice = isset($p['sale_price']) ? (float) $p['sale_price'] : null;
            if ($salePrice !== null && $salePrice > 0 && $depts->count() >= 1) {
                DepartmentProductOverride::updateOrCreate(
                    ['department_id' => $depts[0]->id, 'product_id' => $product->id],
                    ['price' => $salePrice, 'hidden' => false]
                );
            }

            // 2. departman → wholesale_price, 0 ise sale_price kullan
            $wholesalePrice  = isset($p['wholesale_price']) ? (float) $p['wholesale_price'] : 0;
            $dept2Price      = $wholesalePrice > 0 ? $wholesalePrice : $salePrice;
            if ($dept2Price !== null && $dept2Price > 0 && $depts->count() >= 2) {
                DepartmentProductOverride::updateOrCreate(
                    ['department_id' => $depts[1]->id, 'product_id' => $product->id],
                    ['price' => $dept2Price, 'hidden' => false]
                );
            }

            $synced++;
        }

        return response()->json(['synced' => $synced]);
    }

    public function deleteByNexoposId(Request $request, int $nexoposId)
    {
        $tenant  = $request->user()->tenant;
        $deleted = Product::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('nexopos_id', $nexoposId)
            ->delete();

        return response()->json(['success' => true, 'deleted' => $deleted]);
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
