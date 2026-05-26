<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->user()->tenant;

        $query = Order::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->with(['items.product', 'table'])
            ->latest();

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        return response()->json(['data' => $query->take(100)->get()]);
    }

    public function update(Request $request, int $id)
    {
        $tenant = $request->user()->tenant;
        $order = Order::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,preparing,ready,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return response()->json(['data' => $order]);
    }
}
