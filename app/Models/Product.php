<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'category_id', 'name', 'description',
        'price', 'image', 'active', 'nexopos_id',
        'in_stock', 'calories', 'ingredients', 'allergens',
        'is_vegan', 'is_vegetarian', 'has_alcohol', 'has_pork', 'is_featured',
    ];

    protected $casts = [
        'active'         => 'boolean',
        'in_stock'       => 'boolean',
        'price'          => 'float',
        'allergens'      => 'array',
        'is_vegan'       => 'boolean',
        'is_vegetarian'  => 'boolean',
        'has_alcohol'    => 'boolean',
        'has_pork'       => 'boolean',
        'is_featured'    => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function units()
    {
        return $this->hasMany(ProductUnit::class)->orderBy('sort_order');
    }

    public function departmentOverrides()
    {
        return $this->hasMany(DepartmentProductOverride::class);
    }

    public function translationsKeyed(): array
    {
        return $this->translations->keyBy('lang_code')
            ->map(fn($t) => ['name' => $t->name, 'description' => $t->description])
            ->toArray();
    }
}
