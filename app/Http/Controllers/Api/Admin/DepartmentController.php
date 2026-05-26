<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Department;
use App\Models\DepartmentCategoryOverride;
use App\Models\DepartmentProductOverride;
use App\Models\Product;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->user()->tenant;
        $departments = Department::where('tenant_id', $tenant->id)
            ->orderBy('sort_order')
            ->get();

        return response()->json(['data' => $departments]);
    }

    public function store(Request $request)
    {
        $tenant = $request->user()->tenant;

        $data = $request->validate([
            'name'             => 'required|string|max:100',
            'display_name'     => 'nullable|string|max:100',
            'logo'             => 'nullable|string|max:500',
            'banner_image'     => 'nullable|string|max:500',
            'price_multiplier' => 'nullable|numeric|min:0.01|max:99.99',
            'active'           => 'boolean',
            'sort_order'       => 'integer',
        ]);

        $data['tenant_id'] = $tenant->id;
        $data['price_multiplier'] = $data['price_multiplier'] ?? 1.00;

        $dept = Department::create($data);

        return response()->json(['data' => $dept], 201);
    }

    public function update(Request $request, int $id)
    {
        $tenant = $request->user()->tenant;
        $dept = Department::where('tenant_id', $tenant->id)->findOrFail($id);

        $data = $request->validate([
            'name'             => 'sometimes|string|max:100',
            'display_name'     => 'sometimes|nullable|string|max:100',
            'logo'             => 'sometimes|nullable|string|max:500',
            'banner_image'     => 'sometimes|nullable|string|max:500',
            'price_multiplier' => 'sometimes|numeric|min:0.01|max:99.99',
            'active'           => 'sometimes|boolean',
            'sort_order'       => 'sometimes|integer',
        ]);

        $dept->update($data);

        return response()->json(['data' => $dept]);
    }

    public function destroy(Request $request, int $id)
    {
        $tenant = $request->user()->tenant;
        Department::where('tenant_id', $tenant->id)->findOrFail($id)->delete();

        return response()->json(null, 204);
    }

    public function getCategories(Request $request, int $id)
    {
        $tenant = $request->user()->tenant;
        $dept = Department::where('tenant_id', $tenant->id)->findOrFail($id);

        $categories = Category::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'parent_id']);

        $overrides = DepartmentCategoryOverride::where('department_id', $dept->id)
            ->get()
            ->keyBy('category_id');

        $result = $categories->map(fn($c) => [
            'id'        => $c->id,
            'name'      => $c->name,
            'parent_id' => $c->parent_id,
            'hidden'    => $overrides[$c->id]?->hidden ?? false,
        ]);

        return response()->json(['data' => $result]);
    }

    public function updateCategories(Request $request, int $id)
    {
        $tenant = $request->user()->tenant;
        $dept = Department::where('tenant_id', $tenant->id)->findOrFail($id);

        $data = $request->validate([
            'overrides'                => 'required|array',
            'overrides.*.category_id'  => 'required|integer',
            'overrides.*.hidden'       => 'required|boolean',
        ]);

        foreach ($data['overrides'] as $override) {
            $cat = Category::withoutGlobalScopes()
                ->where('tenant_id', $tenant->id)
                ->where('id', $override['category_id'])
                ->first();

            if (! $cat) continue;

            if (! $override['hidden']) {
                DepartmentCategoryOverride::where('department_id', $dept->id)
                    ->where('category_id', $cat->id)
                    ->delete();
            } else {
                DepartmentCategoryOverride::updateOrCreate(
                    ['department_id' => $dept->id, 'category_id' => $cat->id],
                    ['hidden' => true]
                );
            }
        }

        return response()->json(['ok' => true]);
    }

    public function getProducts(Request $request, int $id)
    {
        $tenant = $request->user()->tenant;
        $dept = Department::where('tenant_id', $tenant->id)->findOrFail($id);

        $products = Product::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'image', 'category_id']);

        $overrides = DepartmentProductOverride::where('department_id', $dept->id)
            ->get()
            ->keyBy('product_id');

        $result = $products->map(fn($p) => [
            'id'       => $p->id,
            'name'     => $p->name,
            'price'    => $p->price,
            'image'    => $p->image,
            'category_id' => $p->category_id,
            'hidden'   => $overrides[$p->id]?->hidden ?? false,
            'override_price' => $overrides[$p->id]?->price ?? null,
        ]);

        return response()->json(['data' => $result]);
    }

    public function updateProducts(Request $request, int $id)
    {
        $tenant = $request->user()->tenant;
        $dept = Department::where('tenant_id', $tenant->id)->findOrFail($id);

        $data = $request->validate([
            'overrides'               => 'required|array',
            'overrides.*.product_id'  => 'required|integer',
            'overrides.*.hidden'      => 'required|boolean',
            'overrides.*.price'       => 'nullable|numeric|min:0',
        ]);

        foreach ($data['overrides'] as $override) {
            $product = Product::withoutGlobalScopes()
                ->where('tenant_id', $tenant->id)
                ->where('id', $override['product_id'])
                ->first();

            if (! $product) continue;

            if (! $override['hidden'] && $override['price'] === null) {
                DepartmentProductOverride::where('department_id', $dept->id)
                    ->where('product_id', $product->id)
                    ->delete();
            } else {
                DepartmentProductOverride::updateOrCreate(
                    ['department_id' => $dept->id, 'product_id' => $product->id],
                    ['hidden' => $override['hidden'], 'price' => $override['price']]
                );
            }
        }

        return response()->json(['ok' => true]);
    }
}
