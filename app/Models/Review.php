<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['tenant_id', 'table_id', 'rating', 'comment', 'type', 'name'];

    protected $casts = ['rating' => 'integer'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function table()
    {
        return $this->belongsTo(QrTable::class, 'table_id');
    }
}
