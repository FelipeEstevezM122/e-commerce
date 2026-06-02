<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ORDEN CRUCIAL: Respetar dependencias
        $this->call([
            RoleSeeder::class,      // 1. No depende de nadie
            RankSeeder::class,      // 2. No depende de nadie
            UserSeeder::class,      // 3. Depende de roles y ranks
            BrandSeeder::class,     // 4. No depende de nadie
            CategorySeeder::class,  // 5. No depende de nadie
            ProductSeeder::class,   // 6. Depende de brands y categories
            OrderSeeder::class,     // 7. Depende de users y products
        ]);
    }
}