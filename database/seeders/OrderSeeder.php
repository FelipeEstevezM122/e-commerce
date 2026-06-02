<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $products = Product::all();
        
        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->info('Primero crea usuarios y productos');
            return;
        }
        
        // Crear 50 órdenes
        Order::factory(50)
            ->create()
            ->each(function ($order) use ($users, $products) {
                // Asignar usuario aleatorio
                $order->user_id = $users->random()->id;
                $order->save();
                
                // Cada orden tiene entre 1 y 5 items
                $numItems = rand(1, 5);
                for ($i = 0; $i < $numItems; $i++) {
                    $product = $products->random();
                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'price_when_ordered' => $product->base_price,
                        'quantity' => rand(1, 3),
                    ]);
                }
                
                // Recalcular total de la orden
                $order->total = $order->items->sum(function ($item) {
                    return $item->price_when_ordered * $item->quantity;
                });
                $order->save();
            });
    }
}