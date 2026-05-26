<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\QrTable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->user()->tenant;
        $today = Carbon::today();

        $todayOrders = Order::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->whereDate('created_at', $today)
            ->count();

        $todayRevenue = Order::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->whereDate('created_at', $today)
            ->whereNotIn('status', ['cancelled'])
            ->sum('total');

        $totalProducts = Product::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('active', true)
            ->count();

        $activeTables = QrTable::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('active', true)
            ->count();

        $recentOrders = Order::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->with(['items.product', 'table'])
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'stats' => [
                'todayOrders' => $todayOrders,
                'todayRevenue' => (float) $todayRevenue,
                'totalProducts' => $totalProducts,
                'activeTables' => $activeTables,
            ],
            'recent_orders' => $recentOrders,
        ]);
    }
}
