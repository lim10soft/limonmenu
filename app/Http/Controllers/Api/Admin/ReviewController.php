<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;

        $reviews = Review::where('tenant_id', $tenantId)
            ->with('table')
            ->orderByDesc('created_at')
            ->paginate(30);

        return response()->json($reviews);
    }

    public function export(Request $request)
    {
        $tenantId  = $request->user()->tenant_id;
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $query = Review::where('tenant_id', $tenantId)->with('table');

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        $reviews = $query->orderByDesc('created_at')->get()->map(fn($r) => [
            'id'         => $r->id,
            'rating'     => $r->rating,
            'comment'    => $r->comment,
            'name'       => $r->name,
            'type'       => $r->type,
            'table_name' => $r->table?->name ?? '-',
            'created_at' => $r->created_at?->toDateTimeString(),
        ]);

        return response()->json(['reviews' => $reviews]);
    }

    public function destroy(Request $request, int $id)
    {
        $review = Review::where('tenant_id', $request->user()->tenant_id)->findOrFail($id);
        $review->delete();
        return response()->json(['message' => 'deleted']);
    }
}
