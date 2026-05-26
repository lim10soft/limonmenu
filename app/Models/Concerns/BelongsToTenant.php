<?php

namespace App\Models\Concerns;

use App\Models\Tenant;
use App\Scopes\TenantScope;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (! $model->tenant_id && app()->has('current_tenant')) {
                $model->tenant_id = app('current_tenant')->id;
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
