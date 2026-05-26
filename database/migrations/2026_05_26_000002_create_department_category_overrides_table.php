<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('department_category_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->boolean('hidden')->default(false);
            $table->timestamps();

            $table->unique(['department_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('department_category_overrides');
    }
};
