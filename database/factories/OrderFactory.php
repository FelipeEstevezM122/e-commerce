<?php

namespace Database\Factories;

use App\Models\BillingInfo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    public function definition()
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        // Usar billing_info existente del usuario, o crear uno
        $billing = BillingInfo::where('user_id', $user->id)->first()
                   ?? BillingInfo::factory()->create(['user_id' => $user->id]);

        return [
            'user_id'        => $user->id,
            'billing_info_id'=> $billing->id, // REEMPLAZA: nit, business_name, shipping_address, customer_whatsapp
            'order_number'   => 'ORD-' . str_pad($this->faker->unique()->numberBetween(1, 99999999), 8, '0', STR_PAD_LEFT),
            'total'          => 0, // se recalcula en el seeder
            'status'         => $this->faker->randomElement(['pending', 'paid', 'shipped', 'delivered', 'cancelled']),
            'payment_method' => $this->faker->randomElement(['transferencia', 'efectivo', 'qr', 'deposito']),
        ];
    }
}
