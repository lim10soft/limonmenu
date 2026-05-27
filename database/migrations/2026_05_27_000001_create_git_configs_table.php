<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('git_configs', function (Blueprint $table) {
            $table->id();
            $table->string('git_path', 500)->nullable();
            $table->string('branch', 100)->default('main');
            $table->string('repo_url', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('git_configs');
    }
};
