<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentCategoryOverride extends Model
{
    protected $fillable = ['department_id', 'category_id', 'hidden'];

    protected $casts = ['hidden' => 'boolean'];
}
