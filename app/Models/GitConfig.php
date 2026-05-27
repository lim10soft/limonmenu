<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GitConfig extends Model
{
    protected $fillable = ['git_path', 'branch', 'repo_url'];

    public static function current(): self
    {
        return self::firstOrCreate(['id' => 1], [
            'git_path' => null,
            'branch'   => 'main',
            'repo_url' => null,
        ]);
    }
}
