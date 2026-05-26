<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Department extends Model
{
    protected $fillable = ['tenant_id', 'name', 'display_name', 'logo', 'banner_image', 'token', 'price_multiplier', 'active', 'sort_order'];

    protected $casts = [
        'active'           => 'boolean',
        'price_multiplier' => 'float',
    ];

    protected static function booted(): void
    {
        static::creating(function (Department $dept) {
            if (empty($dept->token)) {
                $dept->token = Str::random(32);
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function overrides(): HasMany
    {
        return $this->hasMany(DepartmentProductOverride::class);
    }

    public function categoryOverrides(): HasMany
    {
        return $this->hasMany(DepartmentCategoryOverride::class);
    }
}
