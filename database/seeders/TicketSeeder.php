<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Ticket;

class TicketSeeder extends Seeder
{
    public function run()
    {
        // Solo crear tickets para órdenes pagadas o entregadas
        $orders = Order::whereIn('status', ['paid', 'delivered'])->get();

        if ($orders->isEmpty()) {
            $this->command->info('No hay órdenes pagadas/entregadas para generar tickets.');
            return;
        }

        foreach ($orders as $index => $order) {
            // Evitar duplicados si ya tiene ticket
            if ($order->ticket()->exists()) {
                continue;
            }

            Ticket::create([
                'order_id'      => $order->id,
                'ticket_number' => 'TKT-' . str_pad($index + 1, 8, '0', STR_PAD_LEFT),
                'issued_at'     => $order->updated_at ?? now(),
            ]);
        }

        $this->command->info('Tickets creados: ' . $orders->count());
    }
}
