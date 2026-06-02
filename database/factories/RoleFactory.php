<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->optional()->paragraph(),
        ];
    }

    // Roles específicos para pruebas
    public function cliente()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'cliente',
                'description' => 'Cliente final',
            ];
        });
    }

    public function mayorista()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'mayorista',
                'description' => 'Cliente mayorista',
            ];
        });
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'admin',
                'description' => 'Administrador del sistema',
            ];
        });
    }
}