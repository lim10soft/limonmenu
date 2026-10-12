<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartmentProductOverride extends Model
{
    protected $fillable = ['department_id', 'product_id', 'hidden', 'price', 'unit_prices'];

    protected $casts = [
        'hidden'      => 'boolean',
        'price'       => 'float',
        'unit_prices' => 'array',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
