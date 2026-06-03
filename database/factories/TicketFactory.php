<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    public function definition()
    {
        return [
            'order_id'      => Order::factory(),
            'ticket_number' => 'TKT-' . str_pad($this->faker->unique()->numberBetween(1, 99999999), 8, '0', STR_PAD_LEFT),
            'issued_at'     => now(),
        ];
    }
}
