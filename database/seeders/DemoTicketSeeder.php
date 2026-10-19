<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DemoTicketSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['cliente', 'mayorista']);
        })
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'admin');
            })
            ->orderBy('id')
            ->get();

        if ($customers->isEmpty()) {
            $this->command->warn('No se encontraron usuarios con rol cliente o mayorista.');
            return;
        }

        $ticketsCreated = 0;
        $demoOrdersCreated = 0;

        foreach ($customers as $customer) {
            $createdDemoOrder = false;
            $createdTicket = DB::transaction(function () use ($customer, &$createdDemoOrder) {
                if ($customer->orders()->whereHas('ticket')->exists()) {
                    return false;
                }

                $existingOrder = $customer->orders()
                    ->whereDoesntHave('ticket')
                    ->orderByDesc('created_at')
                    ->first();

                if ($existingOrder) {
                    Ticket::createForOrder($existingOrder);
                    return true;
                }

                $product = Product::where('stock', '>', 0)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw new RuntimeException(
                        'No hay productos con stock disponible para crear pedidos de demostración.'
                    );
                }

                $quantity = 1;
                $price = $customer->getFinalPrice($product->base_price);
                $order = $customer->orders()->create([
                    'billing_info_id' => $customer->defaultBillingInfo()->value('id'),
                    'order_number' => Order::generateOrderNumber(),
                    'total' => $price * $quantity,
                    'status' => 'pending',
                    'payment_method' => 'efectivo',
                ]);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price_when_ordered' => $price,
                ]);

                Ticket::createForOrder($order);
                $order->update(['status' => 'cancelled']);
                $createdDemoOrder = true;

                return true;
            }, 3);

            if ($createdTicket) {
                $ticketsCreated++;
            }
            if ($createdDemoOrder) {
                $demoOrdersCreated++;
            }
        }

        $this->command->info("Tickets creados: {$ticketsCreated}.");
        $this->command->info("Pedidos ficticios creados y cancelados: {$demoOrdersCreated}.");
    }
}
