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
        $rankIds     = Rank::pluck('id')->toArray();
        $adminRole   = Role::where('name', 'admin')->first();
        $clienteRole = Role::where('name', 'cliente')->first();
        $mayoristaRole = Role::where('name', 'mayorista')->first();

        // ──────────────────────────────────────────────────
        // 1. ADMINISTRADORES REALES
        // ──────────────────────────────────────────────────
        $admins = [
            ['name' => 'Felipe',  'email' => 'felipe@gmail.com'],
            ['name' => 'Steven',  'email' => 'steven@gmail.com'],
            ['name' => 'Hugo',    'email' => 'hugo@gmail.com'],
        ];

        foreach ($admins as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'password'          => bcrypt('12345678'),
                    'email_verified_at' => now(),
                    'rank_id'           => $rankIds[array_rand($rankIds)],
                ]
            );
            if ($adminRole && !$user->roles->contains($adminRole->id)) {
                $user->roles()->attach($adminRole, ['assigned_at' => now()]);
            }
            BillingInfo::firstOrCreate(
                ['user_id' => $user->id, 'is_default' => true],
                [
                    'address'       => 'La Paz, Bolivia',
                    'company_name'  => 'Casatek',
                    'nit'           => '0000000',
                    'business_name' => 'Casatek Admin',
                    'whatsapp'      => '59170000000',
                ]
            );
        }

        // ──────────────────────────────────────────────────
        // 2. USUARIO DE PRUEBA (cliente)
        // ──────────────────────────────────────────────────
        $prueba = User::firstOrCreate(
            ['email' => 'cazas@gmail.com'],
            [
                'name'              => 'Usuario Prueba',
                'password'          => bcrypt('12345678'),
                'email_verified_at' => now(),
                'rank_id'           => $rankIds[array_rand($rankIds)],
            ]
        );
        if ($clienteRole && !$prueba->roles->contains($clienteRole->id)) {
            $prueba->roles()->attach($clienteRole, ['assigned_at' => now()]);
        }
        BillingInfo::firstOrCreate(
            ['user_id' => $prueba->id, 'is_default' => true],
            [
                'address'   => 'Calle Prueba #1, La Paz',
                'whatsapp'  => '59179000000',
            ]
        );

        // ──────────────────────────────────────────────────
        // 3. ADMIN LEGACY (no borrar, puede existir de antes)
        // ──────────────────────────────────────────────────
        $adminLegacy = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Administrador',
                'password' => bcrypt('password'),
                'rank_id'  => $rankIds[array_rand($rankIds)],
            ]
        );
        if ($adminRole && !$adminLegacy->roles->contains($adminRole->id)) {
            $adminLegacy->roles()->attach($adminRole, ['assigned_at' => now()]);
        }

        // ──────────────────────────────────────────────────
        // 4. MAYORISTA DE EJEMPLO
        // ──────────────────────────────────────────────────
        $wholesaler = User::firstOrCreate(
            ['email' => 'mayorista@example.com'],
            [
                'name'     => 'Mayorista Ejemplo',
                'password' => bcrypt('password'),
                'rank_id'  => $rankIds[array_rand($rankIds)],
            ]
        );
        if ($mayoristaRole && !$wholesaler->roles->contains($mayoristaRole->id)) {
            $wholesaler->roles()->attach($mayoristaRole, ['assigned_at' => now()]);
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

        // ──────────────────────────────────────────────────
        // 5. 10 USUARIOS ALEATORIOS
        // ──────────────────────────────────────────────────
        User::factory(10)->create()->each(function ($user) use ($rankIds, $clienteRole, $mayoristaRole) {
            $user->update(['rank_id' => $rankIds[array_rand($rankIds)]]);

            // 70% cliente, 30% mayorista
            $role = rand(1, 10) <= 7 ? $clienteRole : $mayoristaRole;
            if ($role) {
                $user->roles()->attach($role, ['assigned_at' => now()]);
            }

            BillingInfo::create([
                'user_id'       => $user->id,
                'address'       => fake()->address(),
                'company_name'  => $role?->name === 'mayorista' ? fake()->company() : null,
                'nit'           => $role?->name === 'mayorista' ? fake()->numerify('#######') : null,
                'business_name' => $role?->name === 'mayorista' ? fake()->company() : null,
                'whatsapp'      => fake()->numerify('591#######'),
                'is_default'    => true,
            ]);
        });

        $this->command->info('Usuarios creados: 3 admins + 1 prueba + 1 admin legacy + 1 mayorista + 10 aleatorios.');
    }
}
