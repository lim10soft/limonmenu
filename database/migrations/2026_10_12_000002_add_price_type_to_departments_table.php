<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('departments', 'price_type')) {
            return;
        }

        Schema::table('departments', function (Blueprint $table) {
            // normal = POS iç (salon) fiyatı, paket = POS paket fiyatı, null = POS'tan fiyat almaz
            $table->string('price_type', 10)->nullable()->after('price_multiplier');
        });

        // Eski sıra kuralını koru: ilk departman iç fiyat, ikinci paket fiyatı
        $byTenant = DB::table('departments')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['id', 'tenant_id'])
            ->groupBy('tenant_id');

        foreach ($byTenant as $depts) {
            $depts = $depts->values();
            DB::table('departments')->where('id', $depts[0]->id)->update(['price_type' => 'normal']);
            if (isset($depts[1])) {
                DB::table('departments')->where('id', $depts[1]->id)->update(['price_type' => 'paket']);
            }
        }
    }

    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn('price_type');
        });
    }
};
