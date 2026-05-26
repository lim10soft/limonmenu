<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use BelongsToTenant;

    protected $fillable = ['tenant_id', 'nexopos_id', 'parent_id', 'name', 'image', 'sort_order', 'active', 'span'];

    protected $casts = ['active' => 'boolean'];

    public function products()
    {
        return $this->hasMany(Product::class)->where('active', true)->orderBy('name');
    }

    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function translationsKeyed(): array
    {
        return $this->translations->keyBy('lang_code')
            ->map(fn($t) => ['name' => $t->name])
            ->toArray();
    }
}
