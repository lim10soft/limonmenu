<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate(['image' => 'required|image|max:5120']);

        $tenant = $request->user()->tenant;
        $type   = in_array($request->input('type'), ['products', 'categories', 'tenants'])
            ? $request->input('type')
            : 'uploads';

        $file    = $request->file('image');
        $folder  = $type . '/' . $tenant->id;
        $name    = uniqid() . '.' . $file->getClientOriginalExtension();
        $path    = $folder . '/' . $name;

        Storage::disk('public')->put($path, file_get_contents($file->getPathname()));

        return response()->json([
            'url' => Storage::disk('public')->url($path),
        ]);
    }
}
