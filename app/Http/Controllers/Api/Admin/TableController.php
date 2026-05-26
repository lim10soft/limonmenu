<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrTable;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->user()->tenant;
        $tables = QrTable::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $tables]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:50']);

        $tenant = $request->user()->tenant;
        $table = QrTable::withoutGlobalScopes()->create([
            'tenant_id' => $tenant->id,
            'name' => $request->name,
        ]);

        return response()->json(['data' => $table], 201);
    }

    public function update(Request $request, int $id)
    {
        $tenant = $request->user()->tenant;
        $table = QrTable::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->findOrFail($id);

        $table->update($request->only(['name', 'active']));

        return response()->json(['data' => $table]);
    }

    public function destroy(Request $request, int $id)
    {
        $tenant = $request->user()->tenant;
        QrTable::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->findOrFail($id)
            ->delete();

        return response()->json(['message' => 'Silindi.']);
    }
}
