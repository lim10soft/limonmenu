<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QrTable extends Model
{
    use BelongsToTenant;

    protected $fillable = ['tenant_id', 'name', 'token', 'active'];

    protected $casts = ['active' => 'boolean'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($table) {
            if (! $table->token) {
                $table->token = Str::random(32);
            }
        });
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'table_id');
    }
}
