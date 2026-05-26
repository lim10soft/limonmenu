<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\QrTable;
use App\Models\Tenant;
use App\Models\TenantUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::create([
            'name' => 'Demo Restoran',
            'slug' => 'demo',
            'theme_color' => '#f97316',
            'plan' => 'pro',
            'active' => true,
        ]);

        TenantUser::create([
            'tenant_id' => $tenant->id,
            'name' => 'Admin',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        $tables = ['Masa 1', 'Masa 2', 'Masa 3', 'Masa 4', 'Bahçe 1'];
        foreach ($tables as $name) {
            QrTable::create(['tenant_id' => $tenant->id, 'name' => $name, 'active' => true]);
        }

        $categoriesData = [
            ['name' => 'Başlangıçlar', 'sort_order' => 1],
            ['name' => 'Ana Yemekler', 'sort_order' => 2],
            ['name' => 'Pizzalar', 'sort_order' => 3],
            ['name' => 'İçecekler', 'sort_order' => 4],
            ['name' => 'Tatlılar', 'sort_order' => 5],
        ];

        $products = [
            'Başlangıçlar' => [
                ['name' => 'Mercimek Çorbası', 'price' => 85, 'description' => 'Geleneksel kırmızı mercimek çorbası'],
                ['name' => 'Sigara Böreği', 'price' => 95, 'description' => 'Çıtır yufkada peynirli börek (5 adet)'],
                ['name' => 'Humus', 'price' => 110, 'description' => 'Nohut ezmesi, zeytinyağı, kırmızıbiber'],
            ],
            'Ana Yemekler' => [
                ['name' => 'Izgara Köfte', 'price' => 195, 'description' => 'Dana köfte, pilav, salata'],
                ['name' => 'Tavuk Şiş', 'price' => 175, 'description' => 'Marine edilmiş tavuk şiş, lavaş'],
                ['name' => 'Karışık Izgara', 'price' => 280, 'description' => 'Et ve tavuk karışık tabak'],
            ],
            'Pizzalar' => [
                ['name' => 'Margarita', 'price' => 160, 'description' => 'Domates sosu, mozarella, fesleğen'],
                ['name' => 'Karışık Pizza', 'price' => 195, 'description' => 'Sucuk, mantar, biber, zeytin'],
                ['name' => 'Tavuklu Pizza', 'price' => 185, 'description' => 'Tavuk, mısır, mantar, peynir'],
            ],
            'İçecekler' => [
                ['name' => 'Ayran', 'price' => 35, 'description' => null],
                ['name' => 'Kola', 'price' => 45, 'description' => '330ml'],
                ['name' => 'Türk Çayı', 'price' => 25, 'description' => null],
                ['name' => 'Taze Sıkılmış Portakal', 'price' => 75, 'description' => null],
            ],
            'Tatlılar' => [
                ['name' => 'Sütlaç', 'price' => 75, 'description' => 'Fırında sütlaç'],
                ['name' => 'Baklava', 'price' => 95, 'description' => 'Antep fıstıklı baklava (2 dilim)'],
                ['name' => 'Dondurma', 'price' => 65, 'description' => 'Çikolata veya vanilya'],
            ],
        ];

        foreach ($categoriesData as $catData) {
            $cat = Category::create([
                'tenant_id' => $tenant->id,
                'name' => $catData['name'],
                'sort_order' => $catData['sort_order'],
                'active' => true,
            ]);

            foreach ($products[$catData['name']] ?? [] as $p) {
                Product::create([
                    'tenant_id' => $tenant->id,
                    'category_id' => $cat->id,
                    'name' => $p['name'],
                    'description' => $p['description'],
                    'price' => $p['price'],
                    'active' => true,
                ]);
            }
        }
    }
}

