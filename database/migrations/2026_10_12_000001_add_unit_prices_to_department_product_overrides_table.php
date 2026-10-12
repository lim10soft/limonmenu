<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('department_product_overrides', 'unit_prices')) {
            return;
        }

        Schema::table('department_product_overrides', function (Blueprint $table) {
            // Birim sırasına göre departman fiyatları, örn. [310, 350, 470]
            $table->json('unit_prices')->nullable()->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('department_product_overrides', function (Blueprint $table) {
            $table->dropColumn('unit_prices');
        });
    }
};
