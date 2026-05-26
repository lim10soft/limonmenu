<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->user()->tenant;
        $categories = Category::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->whereNull('parent_id')
            ->with(['translations', 'children.translations'])
            ->orderBy('sort_order')
            ->get()
            ->map(fn($c) => $this->format($c, true));

        return response()->json(['data' => $categories]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:100',
            'sort_order'   => 'integer|min:0',
            'image'        => 'nullable|url',
            'parent_id'    => 'nullable|integer',
            'span'         => 'nullable|string|in:normal,wide',
            'translations' => 'array',
            'translations.*.lang_code' => 'required|string|max:10',
            'translations.*.name'      => 'nullable|string|max:100',
        ]);

        $tenantId = $request->user()->tenant_id;

        if (! empty($data['parent_id'])) {
            Category::withoutGlobalScopes()
                ->where('tenant_id', $tenantId)->findOrFail($data['parent_id']);
        }

        $category = Category::withoutGlobalScopes()->create([
            'tenant_id'  => $tenantId,
            'parent_id'  => $data['parent_id'] ?? null,
            'name'       => $data['name'],
            'sort_order' => $data['sort_order'] ?? 0,
            'image'      => $data['image'] ?? null,
            'span'       => $data['span'] ?? 'normal',
            'active'     => true,
        ]);

        $this->saveTranslations($category->id, $data['translations'] ?? []);

        return response()->json(['data' => $this->format($category->load('translations'))], 201);
    }

    public function update(Request $request, int $id)
    {
        $tenant = $request->user()->tenant;
        $category = Category::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)->findOrFail($id);

        $data = $request->validate([
            'name'         => 'string|max:100',
            'sort_order'   => 'integer|min:0',
            'image'        => 'nullable|url',
            'active'       => 'boolean',
            'parent_id'    => 'nullable|integer',
            'span'         => 'nullable|string|in:normal,wide',
            'translations' => 'array',
            'translations.*.lang_code' => 'required|string|max:10',
            'translations.*.name'      => 'nullable|string|max:100',
        ]);

        $fields = array_intersect_key($data, array_flip(['name','sort_order','image','active','parent_id','span']));
        $category->update(array_filter($fields, fn($v) => $v !== null));

        if (array_key_exists('parent_id', $data) && $data['parent_id'] === null) {
            $category->update(['parent_id' => null]);
        }

        if (isset($data['translations'])) {
            $this->saveTranslations($category->id, $data['translations']);
        }

        return response()->json(['data' => $this->format($category->load('translations'))]);
    }

    public function destroy(Request $request, int $id)
    {
        $tenant = $request->user()->tenant;
        Category::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)->findOrFail($id)->delete();

        return response()->json(['message' => 'Silindi.']);
    }

    private function saveTranslations(int $categoryId, array $translations): void
    {
        foreach ($translations as $t) {
            if (empty(trim($t['name']))) {
                CategoryTranslation::where('category_id', $categoryId)
                    ->where('lang_code', $t['lang_code'])->delete();
                continue;
            }
            CategoryTranslation::updateOrCreate(
                ['category_id' => $categoryId, 'lang_code' => $t['lang_code']],
                ['name' => $t['name']]
            );
        }
    }

    private function format(Category $c, bool $withChildren = false): array
    {
        $result = [
            'id'           => $c->id,
            'name'         => $c->name,
            'image'        => $c->image,
            'sort_order'   => $c->sort_order,
            'active'       => $c->active,
            'parent_id'    => $c->parent_id,
            'span'         => $c->span ?? 'normal',
            'translations' => $c->translationsKeyed(),
        ];

        if ($withChildren && $c->relationLoaded('children')) {
            $result['children'] = $c->children->map(fn($child) => $this->format($child))->values()->toArray();
        }

        return $result;
    }
}
