<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Department extends Model
{
    // POS senkronunda bu departmana hangi fiyat yazılır (null = POS'tan fiyat almaz)
    public const PRICE_TYPE_NORMAL = 'normal';
    public const PRICE_TYPE_PAKET  = 'paket';

    protected $fillable = ['tenant_id', 'name', 'display_name', 'logo', 'banner_image', 'token', 'price_multiplier', 'price_type', 'active', 'sort_order'];

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
            // Tür hiç belirtilmediyse (kayıt sırasında açılan varsayılan departman) iç fiyat
            if (! array_key_exists('price_type', $dept->getAttributes()) && static::hasPriceTypeColumn()) {
                $dept->price_type = self::PRICE_TYPE_NORMAL;
            }
        });
    }

    // Migration henüz çalışmadıysa price_type yazılmaya çalışılmasın
    public static function hasPriceTypeColumn(): bool
    {
        static $exists = null;

        return $exists ??= Schema::hasColumn('departments', 'price_type');
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
