<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rank;

class RankSeeder extends Seeder
{
    public function run()
    {
        // Rangos predefinidos del sistema
        $ranks = [
            ['name' => 'Bronce', 'monthly_minimum_purchase' => 0, 'description' => 'Rango inicial'],
            ['name' => 'Plata', 'monthly_minimum_purchase' => 1000, 'description' => 'Compras > Bs. 1000'],
            ['name' => 'Oro', 'monthly_minimum_purchase' => 5000, 'description' => 'Compras > Bs. 5000'],
            ['name' => 'Platino', 'monthly_minimum_purchase' => 10000, 'description' => 'Compras > Bs. 10000'],
        ];

        foreach ($ranks as $rank) {
            Rank::firstOrCreate(
                ['name' => $rank['name']],
                [
                    'monthly_minimum_purchase' => $rank['monthly_minimum_purchase'],
                    'description' => $rank['description']
                ]
            );
        }
    }
}