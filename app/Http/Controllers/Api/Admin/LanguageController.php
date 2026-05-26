<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\TenantLanguage;
use Illuminate\Http\Request;

const ALL_LANG_CODES = ['tr','en','ru','ar','zh','ja','ko','pt','es','de','fr','it'];

class LanguageController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->user()->tenant;

        $existing = TenantLanguage::where('tenant_id', $tenant->id)
            ->pluck('lang_code')->toArray();

        $missing = array_diff(ALL_LANG_CODES, $existing);
        foreach ($missing as $code) {
            TenantLanguage::create([
                'tenant_id'  => $tenant->id,
                'lang_code'  => $code,
                'active'     => $code === 'tr',
                'sort_order' => array_search($code, ALL_LANG_CODES),
            ]);
        }

        $languages = TenantLanguage::where('tenant_id', $tenant->id)
            ->orderBy('sort_order')
            ->get(['id', 'lang_code', 'active', 'sort_order']);

        return response()->json([
            'data'         => $languages,
            'primary_lang' => $tenant->primary_lang ?? 'tr',
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'languages'              => 'required|array',
            'languages.*.lang_code'  => 'required|string|in:' . implode(',', ALL_LANG_CODES),
            'languages.*.active'     => 'required|boolean',
            'languages.*.sort_order' => 'integer|min:0',
            'primary_lang'           => 'nullable|string|in:' . implode(',', ALL_LANG_CODES),
        ]);

        $tenant = $request->user()->tenant;

        foreach ($request->languages as $item) {
            TenantLanguage::updateOrCreate(
                ['tenant_id' => $tenant->id, 'lang_code' => $item['lang_code']],
                ['active' => $item['active'], 'sort_order' => $item['sort_order'] ?? 0]
            );
        }

        if ($request->filled('primary_lang')) {
            $tenant->update(['primary_lang' => $request->primary_lang]);
            // Primary lang must always be active
            TenantLanguage::updateOrCreate(
                ['tenant_id' => $tenant->id, 'lang_code' => $request->primary_lang],
                ['active' => true]
            );
        }

        return response()->json(['message' => 'Dil ayarları kaydedildi.']);
    }
}
