<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;

class ResolveTenant
{
    public function handle(Request $request, Closure $next)
    {
        $slug = $request->route('slug');

        if ($slug) {
            $tenant = Tenant::where('slug', $slug)->where('active', true)->first();

            if (! $tenant) {
                return response()->json(['message' => 'Restoran bulunamadı.'], 404);
            }

            app()->instance('current_tenant', $tenant);
        }

        return $next($request);
    }
}
