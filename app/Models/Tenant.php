<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = [
        'name', 'slug', 'logo', 'plan', 'theme_color',
        'nexopos_url', 'nexopos_token', 'active', 'primary_lang',
        'tables_enabled', 'orders_enabled', 'currency_code',
        'banner_image', 'instagram_url',
    ];

    protected $casts = [
        'active'         => 'boolean',
        'tables_enabled' => 'boolean',
        'orders_enabled' => 'boolean',
    ];

    protected $hidden = ['nexopos_token'];

    public function users(): HasMany
    {
        return $this->hasMany(TenantUser::class);
    }

    public function tables(): HasMany
    {
        return $this->hasMany(QrTable::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function languages(): HasMany
    {
        return $this->hasMany(TenantLanguage::class)->orderBy('sort_order');
    }

    public function activeLanguages(): HasMany
    {
        return $this->hasMany(TenantLanguage::class)->where('active', true)->orderBy('sort_order');
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class)->orderBy('sort_order');
    }
}
