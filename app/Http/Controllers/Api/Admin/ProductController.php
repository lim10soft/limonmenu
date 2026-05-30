<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentProductOverride;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\ProductUnit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->user()->tenant;
        $query = Product::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->with(['category', 'translations', 'units', 'departmentOverrides'])
            ->orderBy('name');

        if ($search = trim($request->get('search', ''))) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $perPage = min((int) $request->get('per_page', 20), 100);
        $paginator = $query->paginate($perPage);

        return response()->json([
            'data' => collect($paginator->items())->map(fn($p) => $this->format($p))->values(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'total'        => $paginator->total(),
                'per_page'     => $paginator->perPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:150',
            'category_id'  => 'required|integer',
            'description'  => 'nullable|string|max:500',
            'price'        => 'required|numeric|min:0',
            'image'        => 'nullable|url',
            'calories'     => 'nullable|integer|min:0',
            'ingredients'  => 'nullable|string|max:2000',
            'allergens'    => 'nullable|array',
            'allergens.*'  => 'string|max:50',
            'is_vegan'     => 'boolean',
            'is_vegetarian'=> 'boolean',
            'has_alcohol'  => 'boolean',
            'has_pork'     => 'boolean',
            'translations' => 'array',
            'translations.*.lang_code'   => 'required|string|max:10',
            'translations.*.name'        => 'nullable|string|max:150',
            'translations.*.description' => 'nullable|string|max:500',
            'department_prices'          => 'array',
            'department_prices.*.department_id' => 'required|integer',
            'department_prices.*.price'         => 'nullable|numeric|min:0',
            'units'                      => 'array',
            'units.*.label'              => 'nullable|string|max:50',
            'units.*.price'              => 'required|numeric|min:0',
            'units.*.sort_order'         => 'integer|min:0',
        ]);

        $product = Product::withoutGlobalScopes()->create([
            'tenant_id'    => $request->user()->tenant_id,
            'category_id'  => $data['category_id'],
            'name'         => $data['name'],
            'description'  => $data['description'] ?? null,
            'price'        => $data['price'],
            'image'        => $data['image'] ?? null,
            'active'       => true,
            'in_stock'     => true,
            'calories'     => $data['calories'] ?? null,
            'ingredients'  => $data['ingredients'] ?? null,
            'allergens'    => $data['allergens'] ?? null,
            'is_vegan'     => $data['is_vegan'] ?? false,
            'is_vegetarian'=> $data['is_vegetarian'] ?? false,
            'has_alcohol'  => $data['has_alcohol'] ?? false,
            'has_pork'     => $data['has_pork'] ?? false,
        ]);

        $this->saveTranslations($product->id, $data['translations'] ?? []);
        $this->saveDepartmentPrices($product->id, $request->user()->tenant_id, $data['department_prices'] ?? []);
        $this->saveUnits($product->id, $data['units'] ?? []);

        return response()->json(['data' => $this->format($product->load(['category','translations','units','departmentOverrides']))], 201);
    }

    public function update(Request $request, int $id)
    {
        $tenant = $request->user()->tenant;
        $product = Product::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)->findOrFail($id);

        $data = $request->validate([
            'name'         => 'string|max:150',
            'category_id'  => 'integer',
            'description'  => 'nullable|string|max:500',
            'price'        => 'numeric|min:0',
            'image'        => 'nullable|url',
            'active'       => 'boolean',
            'in_stock'     => 'boolean',
            'calories'     => 'nullable|integer|min:0',
            'ingredients'  => 'nullable|string|max:2000',
            'allergens'    => 'nullable|array',
            'allergens.*'  => 'string|max:50',
            'is_vegan'     => 'boolean',
            'is_vegetarian'=> 'boolean',
            'has_alcohol'  => 'boolean',
            'has_pork'     => 'boolean',
            'translations' => 'array',
            'translations.*.lang_code'   => 'required|string|max:10',
            'translations.*.name'        => 'nullable|string|max:150',
            'translations.*.description' => 'nullable|string|max:500',
            'department_prices'          => 'array',
            'department_prices.*.department_id' => 'required|integer',
            'department_prices.*.price'         => 'nullable|numeric|min:0',
            'units'                      => 'array',
            'units.*.label'              => 'nullable|string|max:50',
            'units.*.price'              => 'required|numeric|min:0',
            'units.*.sort_order'         => 'integer|min:0',
        ]);

        $fields = ['name','category_id','description','price','image','active','in_stock',
                   'calories','ingredients','allergens','is_vegan','is_vegetarian','has_alcohol','has_pork'];
        $update = array_intersect_key($data, array_flip($fields));
        $product->update($update);

        if (isset($data['translations'])) {
            $this->saveTranslations($product->id, $data['translations']);
        }

        if (isset($data['department_prices'])) {
            $this->saveDepartmentPrices($product->id, $tenant->id, $data['department_prices']);
        }

        if (isset($data['units'])) {
            $this->saveUnits($product->id, $data['units']);
        }

        return response()->json(['data' => $this->format($product->load(['category','translations','units','departmentOverrides']))]);
    }

    public function destroy(Request $request, int $id)
    {
        $tenant = $request->user()->tenant;
        Product::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)->findOrFail($id)->delete();

        return response()->json(['message' => 'Silindi.']);
    }

    private function saveDepartmentPrices(int $productId, int $tenantId, array $deptPrices): void
    {
        $validIds = Department::where('tenant_id', $tenantId)->pluck('id')->flip();

        foreach ($deptPrices as $dp) {
            if (! isset($validIds[$dp['department_id']])) continue;

            $price = $dp['price'] ?? null;
            $override = DepartmentProductOverride::where('department_id', $dp['department_id'])
                ->where('product_id', $productId)
                ->first();

            if ($price === null) {
                if ($override && ! $override->hidden) {
                    $override->delete();
                } elseif ($override && $override->hidden) {
                    $override->update(['price' => null]);
                }
            } else {
                DepartmentProductOverride::updateOrCreate(
                    ['department_id' => $dp['department_id'], 'product_id' => $productId],
                    ['price' => $price]
                );
            }
        }
    }

    private function saveUnits(int $productId, array $units): void
    {
        ProductUnit::where('product_id', $productId)->delete();
        foreach ($units as $i => $u) {
            ProductUnit::create([
                'product_id' => $productId,
                'label'      => $u['label'] ?? null,
                'price'      => $u['price'],
                'sort_order' => $u['sort_order'] ?? $i,
            ]);
        }
    }

    private function saveTranslations(int $productId, array $translations): void
    {
        foreach ($translations as $t) {
            if (empty(trim($t['name'] ?? ''))) {
                ProductTranslation::where('product_id', $productId)
                    ->where('lang_code', $t['lang_code'])->delete();
                continue;
            }
            ProductTranslation::updateOrCreate(
                ['product_id' => $productId, 'lang_code' => $t['lang_code']],
                ['name' => $t['name'], 'description' => $t['description'] ?? null]
            );
        }
    }

    private function format(Product $p): array
    {
        return [
            'id'            => $p->id,
            'name'          => $p->name,
            'description'   => $p->description,
            'price'         => $p->price,
            'image'         => $p->image,
            'active'        => $p->active,
            'in_stock'      => $p->in_stock,
            'calories'      => $p->calories,
            'ingredients'   => $p->ingredients,
            'allergens'     => $p->allergens ?? [],
            'is_vegan'      => $p->is_vegan,
            'is_vegetarian' => $p->is_vegetarian,
            'has_alcohol'   => $p->has_alcohol,
            'has_pork'      => $p->has_pork,
            'category_id'   => $p->category_id,
            'category'      => $p->category ? ['id' => $p->category->id, 'name' => $p->category->name] : null,
            'translations'      => $p->translationsKeyed(),
            'units'             => $p->relationLoaded('units')
                ? $p->units->map(fn($u) => ['id' => $u->id, 'label' => $u->label, 'price' => $u->price, 'sort_order' => $u->sort_order])->values()
                : [],
            'department_prices' => $p->relationLoaded('departmentOverrides')
                ? $p->departmentOverrides->map(fn($o) => ['department_id' => $o->department_id, 'price' => $o->price])->values()
                : [],
        ];
    }
}
