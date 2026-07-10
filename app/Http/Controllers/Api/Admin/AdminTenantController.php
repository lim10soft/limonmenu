<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryTranslation;
use App\Models\Department;
use App\Models\DepartmentCategoryOverride;
use App\Models\DepartmentProductOverride;
use App\Models\OrderItem;
use App\Models\ProductTranslation;
use App\Models\ProductUnit;
use App\Models\Review;
use App\Models\Tenant;
use App\Models\TenantLanguage;
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
            'slug'            => 'required|string|max:100',
            'restaurant_name' => 'required|string|max:150',
            'user_name'       => 'required|string|max:150',
            'email'           => 'required|email|max:150',
            'password'        => 'required|string|min:4',
        ]);

        $tenant = Tenant::updateOrCreate(
            ['slug' => $request->slug],
            [
                'name'           => $request->restaurant_name,
                'active'         => true,
                'plan'           => 'free',
                'theme_color'    => '#f97316',
                'primary_lang'   => 'tr',
                'tables_enabled' => false,
                'orders_enabled' => false,
            ]
        );

        // Varsayılan "Menü" departmanı (sadece ilk kurulumda)
        if ($tenant->wasRecentlyCreated) {
            Department::create([
                'tenant_id'        => $tenant->id,
                'name'             => 'Menü',
                'active'           => true,
                'sort_order'       => 1,
                'price_multiplier' => 1.00,
            ]);
        }

        // Tüm diller aktif olarak oluştur
        $allLangs = ['tr','en','ru','ar','zh','ja','ko','pt','es','de','fr','it'];
        foreach ($allLangs as $i => $code) {
            TenantLanguage::updateOrCreate(
                ['tenant_id' => $tenant->id, 'lang_code' => $code],
                ['active' => true, 'sort_order' => $i]
            );
        }

        $user = TenantUser::updateOrCreate(
            ['tenant_id' => $tenant->id, 'email' => $request->email],
            ['name' => $request->user_name, 'password' => Hash::make($request->password), 'role' => 'owner']
        );

        // Sanctum integration token oluştur (login'de yeniden oluşturulmaması için)
        $user->tokens()->where('name', 'integration')->delete();
        $plainToken = $user->createToken('integration')->plainTextToken;
        $tenant->update(['api_token' => $plainToken]);

        return response()->json([
            'success'   => true,
            'tenant_id' => $tenant->id,
            'api_token' => $plainToken,
        ]);
    }

    public function syncAssets(Request $request)
    {
        $secret = env('PROVISION_SECRET');
        if (!$secret || $request->header('X-Provision-Secret') !== $secret) {
            return response()->json(['error' => 'Yetkisiz'], 401);
        }

        $request->validate([
            'slug'          => 'required|string|max:100',
            'name'          => 'nullable|string|max:150',
            'logo'          => 'nullable|string|max:500',
            'banner_image'  => 'nullable|string|max:500',
            'instagram_url' => 'nullable|string|max:500',
        ]);

        $tenant = Tenant::where('slug', $request->slug)->first();
        if (!$tenant) {
            return response()->json(['error' => 'Tenant bulunamadı'], 404);
        }

        $tenantUpdate = [];
        if ($request->filled('name'))       $tenantUpdate['name']          = $request->name;
        if ($request->has('logo'))          $tenantUpdate['logo']          = $request->logo;
        if ($request->has('banner_image'))  $tenantUpdate['banner_image']  = $request->banner_image;
        if ($request->has('instagram_url')) $tenantUpdate['instagram_url'] = $request->instagram_url;

        if (!empty($tenantUpdate)) {
            $tenant->update($tenantUpdate);
        }

        // İlk departmanın banner_image'ını güncelle
        if ($request->has('banner_image')) {
            Department::where('tenant_id', $tenant->id)
                ->orderBy('sort_order')
                ->first()
                ?->update(['banner_image' => $request->banner_image]);
        }

        return response()->json(['success' => true]);
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
