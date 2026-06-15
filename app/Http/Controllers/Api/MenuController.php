<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Department;
use App\Models\DepartmentCategoryOverride;
use App\Models\DepartmentProductOverride;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\QrTable;
use App\Models\Review;
use App\Models\TenantLanguage;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $tenant     = app('current_tenant');
        $tableToken = $request->route('tableToken');
        $deptToken  = $request->route('deptToken');

        $table = null;
        if ($tableToken && $tenant->tables_enabled) {
            $table = QrTable::withoutGlobalScopes()
                ->where('tenant_id', $tenant->id)
                ->where('token', $tableToken)
                ->where('active', true)
                ->firstOrFail();
        }

        $department = null;
        if ($deptToken) {
            $department = Department::where('tenant_id', $tenant->id)
                ->where('token', $deptToken)
                ->where('active', true)
                ->with('overrides')
                ->first();
        }

        $activeLangs = TenantLanguage::where('tenant_id', $tenant->id)
            ->where('active', true)
            ->orderBy('sort_order')
            ->pluck('lang_code')
            ->toArray();

        // Primary lang is always allowed even if somehow not in active list
        $primaryLang = $tenant->primary_lang ?? 'tr';
        if (! in_array($primaryLang, $activeLangs)) {
            array_unshift($activeLangs, $primaryLang);
        }

        $requestedLang = $request->query('lang', $primaryLang);
        if (! in_array($requestedLang, $activeLangs)) {
            $requestedLang = $primaryLang;
        }

        $overridesMap = [];
        $hiddenCategoryIds = [];
        if ($department) {
            foreach ($department->overrides as $o) {
                $overridesMap[$o->product_id] = $o;
            }
            $hiddenCategoryIds = DepartmentCategoryOverride::where('department_id', $department->id)
                ->where('hidden', true)
                ->pluck('category_id')
                ->toArray();
        }

        // Önerilen ürünler (is_featured = true)
        $featuredProducts = \App\Models\Product::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('active', true)
            ->where('is_featured', true)
            ->with(['translations', 'units'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(fn($p) => $this->formatProduct($p, $requestedLang, $overridesMap, $department))
            ->filter(fn($p) => ! $p['hidden'])
            ->values();

        // Load root categories with their children and products
        $rootCategories = Category::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->whereNull('parent_id')
            ->where('active', true)
            ->with([
                'translations',
                'children' => fn($q) => $q->where('active', true)->orderBy('sort_order'),
                'children.translations',
                'children.products' => fn($q) => $q->where('active', true)->orderBy('sort_order')->orderBy('name'),
                'children.products.translations',
                'children.products.units',
                'products' => fn($q) => $q->where('active', true)->orderBy('sort_order')->orderBy('name'),
                'products.translations',
                'products.units',
            ])
            ->orderBy('sort_order')
            ->get();

        $categories = $rootCategories
            ->filter(fn($cat) => ! in_array($cat->id, $hiddenCategoryIds))
            ->map(function ($cat) use ($requestedLang, $overridesMap, $department, $hiddenCategoryIds) {
                return $this->formatCategory($cat, $requestedLang, $overridesMap, $department, $hiddenCategoryIds);
            })->filter(function ($cat) {
            $directProducts = count($cat['products']);
            $childProducts = array_sum(array_map(fn($c) => count($c['products']), $cat['children']));
            return $directProducts + $childProducts > 0;
        })->values();

        return response()->json([
            'tenant' => $tenant->only([
                'id', 'name', 'slug', 'logo', 'theme_color', 'plan',
                'tables_enabled', 'orders_enabled', 'currency_code', 'banner_image', 'instagram_url',
            ]),
            'table'            => $table,
            'department'       => $department ? [
                'id'           => $department->id,
                'name'         => $department->name,
                'display_name' => $department->display_name,
                'logo'         => $department->logo,
                'banner_image' => $department->banner_image,
            ] : null,
            'active_languages'  => $activeLangs,
            'lang'              => $requestedLang,
            'categories'        => $categories,
            'featured_products' => $featuredProducts,
        ]);
    }

    private function formatCategory($cat, string $lang, array $overridesMap, ?Department $department, array $hiddenCategoryIds = []): array
    {
        $catTrans = $cat->translations->firstWhere('lang_code', $lang);

        $products = $cat->products
            ->map(fn($p) => $this->formatProduct($p, $lang, $overridesMap, $department))
            ->filter(fn($p) => ! $p['hidden'])
            ->values();

        $children = [];
        if ($cat->relationLoaded('children')) {
            $children = $cat->children
                ->filter(fn($child) => ! in_array($child->id, $hiddenCategoryIds))
                ->map(function ($child) use ($lang, $overridesMap, $department, $hiddenCategoryIds) {
                    $childTrans = $child->translations->firstWhere('lang_code', $lang);
                    $childProducts = $child->products
                        ->map(fn($p) => $this->formatProduct($p, $lang, $overridesMap, $department))
                        ->filter(fn($p) => ! $p['hidden'])
                        ->values();
                    return [
                        'id'       => $child->id,
                        'name'     => $childTrans?->name ?? $child->name,
                        'image'    => $child->image,
                        'span'     => $child->span ?? 'normal',
                        'products' => $childProducts,
                    ];
                })->filter(fn($c) => count($c['products']) > 0)->values()->toArray();
        }

        return [
            'id'       => $cat->id,
            'name'     => $catTrans?->name ?? $cat->name,
            'image'    => $cat->image,
            'span'     => $cat->span ?? 'normal',
            'products' => $products,
            'children' => $children,
        ];
    }

    private function formatProduct($product, string $lang, array $overridesMap, ?Department $department): array
    {
        $trans = $product->translations->firstWhere('lang_code', $lang);
        $override = $overridesMap[$product->id] ?? null;

        $price = $product->price;
        if ($override?->price !== null) {
            $price = $override->price;
        } elseif ($department && $department->price_multiplier != 1.0) {
            $price = round($product->price * $department->price_multiplier, 2);
        }

        $units = [];
        if ($product->relationLoaded('units') && $product->units->count() > 0) {
            $units = $product->units->map(function ($u) use ($override, $department, $product) {
                $unitPrice = $u->price;
                if ($override?->price !== null) {
                    // Scale unit price proportionally when there's a department override
                    $ratio = $product->price > 0 ? $override->price / $product->price : 1;
                    $unitPrice = round($u->price * $ratio, 2);
                } elseif ($department && $department->price_multiplier != 1.0) {
                    $unitPrice = round($u->price * $department->price_multiplier, 2);
                }
                return ['label' => $u->label, 'price' => $unitPrice];
            })->values()->toArray();
        }

        return [
            'id'            => $product->id,
            'name'          => $trans?->name ?? $product->name,
            'description'   => $trans?->description ?? $product->description,
            'price'         => $price,
            'image'         => $product->image,
            'hidden'        => $override?->hidden ?? false,
            'in_stock'      => $product->in_stock,
            'calories'      => $product->calories,
            'ingredients'   => $product->ingredients,
            'allergens'     => $product->allergens ?? [],
            'is_vegan'      => $product->is_vegan,
            'is_vegetarian' => $product->is_vegetarian,
            'has_alcohol'   => $product->has_alcohol,
            'has_pork'      => $product->has_pork,
            'units'         => $units,
        ];
    }

    public function review(Request $request, string $slug)
    {
        $tenant = app('current_tenant');

        $validated = $request->validate([
            'rating'  => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'type'    => 'nullable|in:review,complaint',
            'name'    => 'nullable|string|max:100',
        ]);

        $review = Review::create([
            'tenant_id' => $tenant->id,
            'rating'    => $validated['rating'] ?? null,
            'comment'   => $validated['comment'] ?? null,
            'type'      => $validated['type'] ?? 'review',
            'name'      => $validated['name'] ?? null,
        ]);

        return response()->json(['message' => 'ok', 'id' => $review->id], 201);
    }

    public function order(Request $request, string $slug)
    {
        $tenant = app('current_tenant');

        $rules = [
            'note'        => 'nullable|string|max:500',
            'items'       => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity'   => 'required|integer|min:1|max:50',
            'items.*.note'       => 'nullable|string|max:200',
        ];

        if ($tenant->tables_enabled) {
            $rules['table_token'] = 'required|string';
        }

        $validated = $request->validate($rules);

        $table = null;
        if ($tenant->tables_enabled && isset($validated['table_token'])) {
            $table = QrTable::withoutGlobalScopes()
                ->where('tenant_id', $tenant->id)
                ->where('token', $validated['table_token'])
                ->where('active', true)
                ->firstOrFail();
        }

        
        $order = Order::withoutGlobalScopes()->create([
            'tenant_id' => $tenant->id,
            'table_id'  => $table?->id,
            'note'      => $validated['note'] ?? null,
            'status'    => 'pending',
            'total'     => 0,
        ]);

        $total = 0;
        foreach ($validated['items'] as $item) {
            $product = \App\Models\Product::withoutGlobalScopes()
                ->where('tenant_id', $tenant->id)
                ->where('id', $item['product_id'])
                ->where('active', true)
                ->firstOrFail();

            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'unit_price' => $product->price,
                'note'       => $item['note'] ?? null,
            ]);

            $total += $product->price * $item['quantity'];
        }

        $order->update(['total' => $total]);

        return response()->json([
            'order' => $order->load(['items.product', 'table']),
        ], 201);
    }
}
