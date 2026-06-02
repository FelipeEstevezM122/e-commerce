<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Rank;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Usuario administrador específico
        $admin = User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'user_type' => 'final',
        ]);
        
        // Asignar rol admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $admin->roles()->attach($adminRole, ['assigned_at' => now()]);
        }
        
        // 2. Usuario mayorista específico
        $wholesaler = User::factory()->create([
            'name' => 'Mayorista Ejemplo',
            'email' => 'mayorista@example.com',
            'user_type' => 'mayorista',
            'company_name' => 'Distribuidora Mayorista SRL',
        ]);
        
        // 3. Crear 50 usuarios aleatorios con factories
        User::factory(50)
            ->create()
            ->each(function ($user) {
                // Asignar rol aleatorio a cada usuario
                $role = Role::inRandomOrder()->first();
                $user->roles()->attach($role, ['assigned_at' => now()]);
                
                // Asignar rango según sus compras (simulado)
                $rank = Rank::inRandomOrder()->first();
                $user->rank_id = $rank->id;
                $user->save();
            });
    }
}