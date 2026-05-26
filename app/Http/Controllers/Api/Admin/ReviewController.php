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

    public function destroy(Request $request, int $id)
    {
        $review = Review::where('tenant_id', $request->user()->tenant_id)->findOrFail($id);
        $review->delete();
        return response()->json(['message' => 'deleted']);
    }
}
