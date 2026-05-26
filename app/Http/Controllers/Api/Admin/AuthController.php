<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantLanguage;
use App\Models\TenantUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'restaurant_name'      => 'required|string|max:100',
            'email'                => 'required|email|unique:tenant_users,email',
            'password'             => 'required|string|min:6|confirmed',
        ]);

        $baseSlug = Str::slug($data['restaurant_name'], '-');
        $slug = $baseSlug ?: 'restoran';
        $i = 1;
        while (Tenant::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        $tenant = Tenant::create([
            'name'            => $data['restaurant_name'],
            'slug'            => $slug,
            'plan'            => 'free',
            'theme_color'     => '#f97316',
            'active'          => true,
            'tables_enabled'  => true,
            'orders_enabled'  => true,
            'primary_lang'    => 'tr',
        ]);

        TenantLanguage::create([
            'tenant_id'  => $tenant->id,
            'lang_code'  => 'tr',
            'active'     => true,
            'sort_order' => 0,
        ]);

        $user = TenantUser::create([
            'tenant_id' => $tenant->id,
            'name'      => $data['restaurant_name'],
            'email'     => $data['email'],
            'password'  => $data['password'],
            'role'      => 'owner',
        ]);

        $token = $user->createToken('admin')->plainTextToken;

        return response()->json([
            'token'   => $token,
            'user'    => $user->only(['id', 'name', 'email', 'role', 'tenant_id']),
            'tenant'  => $tenant,
            'app_url' => config('app.url'),
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = TenantUser::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['E-posta veya şifre hatalı.'],
            ]);
        }

        $user->tokens()->delete();
        $token = $user->createToken('admin')->plainTextToken;

        return response()->json([
            'token'   => $token,
            'user'    => $user->only(['id', 'name', 'email', 'role', 'tenant_id']),
            'tenant'  => $user->tenant,
            'app_url' => config('app.url'),
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'user'    => $user->only(['id', 'name', 'email', 'role', 'tenant_id']),
            'tenant'  => $user->tenant,
            'app_url' => config('app.url'),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Çıkış yapıldı.']);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current' => 'required|string',
            'new' => 'required|string|min:6',
        ]);

        $user = $request->user();

        if (! Hash::check($request->current, $user->password)) {
            throw ValidationException::withMessages(['current' => ['Mevcut şifre hatalı.']]);
        }

        $user->update(['password' => $request->new]);

        return response()->json(['message' => 'Şifre güncellendi.']);
    }
}
