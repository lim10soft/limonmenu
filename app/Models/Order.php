<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use BelongsToTenant;

    protected $fillable = ['tenant_id', 'table_id', 'status', 'total', 'note'];

    protected $casts = ['total' => 'float'];

    public function table()
    {
        return $this->belongsTo(QrTable::class, 'table_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
