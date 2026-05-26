<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenant_users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropUnique('tenant_users_tenant_id_email_unique');
            $table->unsignedBigInteger('tenant_id')->nullable()->change();
        });

        DB::statement("ALTER TABLE tenant_users MODIFY role ENUM('owner','staff','superadmin') NOT NULL DEFAULT 'owner'");

        Schema::table('tenant_users', function (Blueprint $table) {
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tenant_users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
        });

        DB::statement("ALTER TABLE tenant_users MODIFY role ENUM('owner','staff') NOT NULL DEFAULT 'owner'");

        Schema::table('tenant_users', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable(false)->change();
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->unique(['tenant_id', 'email']);
        });
    }
};
