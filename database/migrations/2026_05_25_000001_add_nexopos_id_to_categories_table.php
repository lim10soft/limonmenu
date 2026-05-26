<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('nexopos_id')->nullable()->after('tenant_id');
            $table->index(['tenant_id', 'nexopos_id']);
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'nexopos_id']);
            $table->dropColumn('nexopos_id');
        });
    }
};
