<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Ticket;

class TicketSeeder extends Seeder
{
    public function run()
    {
        // Solo crear tickets para órdenes pagadas.
        $orders = Order::where('status', 'paid')->get();

        if ($orders->isEmpty()) {
            $this->command->info('No hay órdenes pagadas/entregadas para generar tickets.');
            return;
        }

        foreach ($orders as $order) {
            // Evitar duplicados si ya tiene ticket
            if ($order->ticket()->exists()) {
                continue;
            }

            Ticket::createForOrder($order);
        }

        $this->command->info('Tickets creados: ' . $orders->count());
    }
}
