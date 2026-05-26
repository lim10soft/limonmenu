<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'quantity', 'unit_price', 'note'];

    protected $casts = ['unit_price' => 'float'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
