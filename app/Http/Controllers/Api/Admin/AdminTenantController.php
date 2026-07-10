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
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AdminTenantController extends Controller
{
    public function provision(Request $request)
    {
        $secret = env('PROVISION_SECRET');
        if (!$secret || $request->header('X-Provision-Secret') !== $secret) {
            return response()->json(['error' => 'Yetkisiz'], 401);
        }

        $request->validate([
            'slug'     => 'required|string|max:100',
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|max:150',
            'password' => 'required|string|min:4',
        ]);

        $apiToken = bin2hex(random_bytes(32));

        $tenant = Tenant::updateOrCreate(
            ['slug' => $request->slug],
            ['name' => $request->name, 'api_token' => $apiToken, 'active' => true]
        );

        TenantUser::updateOrCreate(
            ['tenant_id' => $tenant->id, 'email' => $request->email],
            ['name' => $request->name, 'password' => Hash::make($request->password), 'role' => 'owner']
        );

        return response()->json([
            'success'   => true,
            'tenant_id' => $tenant->id,
            'api_token' => $apiToken,
        ]);
    }

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
