<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryTranslation;
use App\Models\DepartmentCategoryOverride;
use App\Models\DepartmentProductOverride;
use App\Models\OrderItem;
use App\Models\ProductTranslation;
use App\Models\ProductUnit;
use App\Models\Review;
use App\Models\Tenant;
use App\Models\TenantUser;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AdminTenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::withCount('users')->orderByDesc('id')->get();
        return response()->json($tenants);
    }

    public function update(Request $request, int $id)
    {
        $tenant = Tenant::findOrFail($id);

        $data = $request->validate([
            'name'   => 'sometimes|string|max:100',
            'slug'   => 'sometimes|string|max:100|unique:tenants,slug,' . $id,
            'plan'   => 'sometimes|in:free,starter,pro',
            'active' => 'sometimes|boolean',
        ]);

        $tenant->update($data);

        return response()->json($tenant->withCount('users')->first() ?? $tenant->fresh());
    }

    public function destroy(int $id)
    {
        $tenant = Tenant::findOrFail($id);

        $deptIds = $tenant->departments()->pluck('id');
        if ($deptIds->isNotEmpty()) {
            DepartmentProductOverride::whereIn('department_id', $deptIds)->delete();
            DepartmentCategoryOverride::whereIn('department_id', $deptIds)->delete();
        }
        $tenant->departments()->delete();

        $orderIds = $tenant->orders()->pluck('id');
        if ($orderIds->isNotEmpty()) {
            OrderItem::whereIn('order_id', $orderIds)->delete();
        }
        $tenant->orders()->delete();

        $productIds = $tenant->products()->pluck('id');
        if ($productIds->isNotEmpty()) {
            ProductTranslation::whereIn('product_id', $productIds)->delete();
            ProductUnit::whereIn('product_id', $productIds)->delete();
        }
        $tenant->products()->delete();

        $categoryIds = $tenant->categories()->pluck('id');
        if ($categoryIds->isNotEmpty()) {
            CategoryTranslation::whereIn('category_id', $categoryIds)->delete();
        }
        $tenant->categories()->delete();

        $tenant->tables()->delete();
        $tenant->languages()->delete();

        Review::where('tenant_id', $tenant->id)->delete();

        $userIds = $tenant->users()->pluck('id');
        if ($userIds->isNotEmpty()) {
            PersonalAccessToken::whereIn('tokenable_id', $userIds)
                ->where('tokenable_type', TenantUser::class)
                ->delete();
        }
        $tenant->users()->delete();

        $tenant->delete();

        return response()->json(['message' => 'Müşteri silindi.']);
    }
}
