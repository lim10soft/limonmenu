<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantLanguage extends Model
{
    protected $fillable = ['tenant_id', 'lang_code', 'active', 'sort_order'];

    protected $casts = ['active' => 'boolean'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
