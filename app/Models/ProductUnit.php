<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    protected $fillable = ['product_id', 'label', 'price', 'sort_order'];

    protected $casts = ['price' => 'float', 'sort_order' => 'integer'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
