<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('in_stock')->default(true)->after('active');
            $table->integer('calories')->nullable()->after('in_stock');
            $table->text('ingredients')->nullable()->after('calories');
            $table->json('allergens')->nullable()->after('ingredients');
            $table->boolean('is_vegan')->default(false)->after('allergens');
            $table->boolean('is_vegetarian')->default(false)->after('is_vegan');
            $table->boolean('has_alcohol')->default(false)->after('is_vegetarian');
            $table->boolean('has_pork')->default(false)->after('has_alcohol');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['in_stock', 'calories', 'ingredients', 'allergens', 'is_vegan', 'is_vegetarian', 'has_alcohol', 'has_pork']);
        });
    }
};
