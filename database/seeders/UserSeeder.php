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
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Administrador',
                'password' => bcrypt('password'),
                'user_type' => 'final',
                'rank_id'  => Rank::inRandomOrder()->first()?->id,
            ]
        );

        // Asignar rol admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && !$admin->roles->contains($adminRole->id)) {
            $admin->roles()->attach($adminRole, ['assigned_at' => now()]);
        }

        // 2. Usuario mayorista específico
        $wholesaler = User::firstOrCreate(
            ['email' => 'mayorista@example.com'],
            [
                'name'         => 'Mayorista Ejemplo',
                'password'     => bcrypt('password'),
                'user_type'    => 'mayorista',
                'company_name' => 'Distribuidora Mayorista SRL',
                'rank_id'      => Rank::inRandomOrder()->first()?->id,
            ]
        );

        // 3. Crear 50 usuarios aleatorios con factories
        $rankIds = Rank::pluck('id')->toArray();

        User::factory(50)
            ->create()
            ->each(function ($user) use ($rankIds) {
                // Asignar rol aleatorio a cada usuario
                $role = Role::inRandomOrder()->first();
                if ($role) {
                    $user->roles()->attach($role, ['assigned_at' => now()]);
                }

                // Asignar rango existente (sin crear nuevos via factory)
                $user->rank_id = $rankIds[array_rand($rankIds)];
                $user->save();
            });
    }
}
