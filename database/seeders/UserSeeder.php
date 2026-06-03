<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Rank;
use App\Models\BillingInfo;

class UserSeeder extends Seeder
{
    public function run()
    {
        $rankIds = Rank::pluck('id')->toArray();

        // 1. Administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Administrador',
                'password' => bcrypt('password'),
                'rank_id'  => $rankIds[array_rand($rankIds)],
            ]
        );
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && !$admin->roles->contains($adminRole->id)) {
            $admin->roles()->attach($adminRole, ['assigned_at' => now()]);
        }
        // Billing info del admin
        BillingInfo::firstOrCreate(
            ['user_id' => $admin->id, 'is_default' => true],
            [
                'address'       => 'Av. Principal #100, La Paz',
                'company_name'  => 'Sistema Admin',
                'nit'           => '1234567',
                'business_name' => 'Admin SRL',
                'whatsapp'      => '59170000000',
            ]
        );

        // 2. Mayorista de ejemplo
        $wholesaler = User::firstOrCreate(
            ['email' => 'mayorista@example.com'],
            [
                'name'     => 'Mayorista Ejemplo',
                'password' => bcrypt('password'),
                'rank_id'  => $rankIds[array_rand($rankIds)],
            ]
        );
        $wholesaleRole = Role::where('name', 'mayorista')->first();
        if ($wholesaleRole && !$wholesaler->roles->contains($wholesaleRole->id)) {
            $wholesaler->roles()->attach($wholesaleRole, ['assigned_at' => now()]);
        }
        BillingInfo::firstOrCreate(
            ['user_id' => $wholesaler->id, 'is_default' => true],
            [
                'address'       => 'Calle Comercio #55, Cochabamba',
                'company_name'  => 'Distribuidora Mayorista SRL',
                'nit'           => '9876543',
                'business_name' => 'Distribuidora Mayorista SRL',
                'whatsapp'      => '59171111111',
            ]
        );

        // 3. 50 usuarios aleatorios
        $clienteRole    = Role::where('name', 'cliente')->first();
        $mayoristRole   = Role::where('name', 'mayorista')->first();
        $allRoles       = Role::all();

        User::factory(50)->create()->each(function ($user) use ($rankIds, $allRoles, $clienteRole, $mayoristRole) {
            // Asignar rank existente
            $user->update(['rank_id' => $rankIds[array_rand($rankIds)]]);

            // Asignar rol: 70% cliente, 30% mayorista
            $role = rand(1, 10) <= 7 ? $clienteRole : $mayoristRole;
            if ($role) {
                $user->roles()->attach($role, ['assigned_at' => now()]);
            }

            // Crear billing_info (1 predeterminado, opcionalmente otro)
            BillingInfo::create([
                'user_id'      => $user->id,
                'address'      => fake()->address(),
                'company_name' => $role?->name === 'mayorista' ? fake()->company() : null,
                'nit'          => $role?->name === 'mayorista' ? fake()->numerify('#######') : null,
                'business_name'=> $role?->name === 'mayorista' ? fake()->company() : null,
                'whatsapp'     => fake()->numerify('591#######'),
                'is_default'   => true,
            ]);
        });
    }
}
