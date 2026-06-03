<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,               // 1. Sin dependencias
            RankSeeder::class,               // 2. Sin dependencias
            UserSeeder::class,               // 3. Depende de roles, ranks → crea billing_info internamente
            BrandSeeder::class,              // 4. Sin dependencias
            CategorySeeder::class,           // 5. Sin dependencias
            ProductSeeder::class,            // 6. Depende de brands, categories
            CartSeeder::class,               // 7. Depende de users, products
            OrderSeeder::class,              // 8. Depende de users, billing_info, products
            TicketSeeder::class,             // 9. Depende de orders
            AccumulatedPurchaseSeeder::class,// 10. Depende de users, orders
        ]);
    }
}
