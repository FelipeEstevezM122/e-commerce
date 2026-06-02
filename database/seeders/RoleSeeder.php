<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Datos fijos que siempre deben existir
        $roles = [
            ['name' => 'cliente', 'description' => 'Cliente final'],
            ['name' => 'mayorista', 'description' => 'Cliente mayorista'],
            ['name' => 'admin', 'description' => 'Administrador del sistema'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']], 
                ['description' => $role['description']]
            );
        }
    }
}