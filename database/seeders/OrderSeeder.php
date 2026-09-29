<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\BillingInfo;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $users    = User::with('billingInfo')->get();
        $products = Product::all();

        if ($users->isEmpty()) {
            $this->command->warn('OrderSeeder: no hay usuarios. Ejecuta UserSeeder primero.');
            return;
        }

        if ($products->isEmpty()) {
            $this->command->warn('OrderSeeder: no hay productos en la BD.');
            $this->command->warn('Agrega productos desde el panel admin (/admin/products/create)');
            $this->command->warn('y luego corre: php artisan db:seed --class=OrderSeeder');
            return;
        }

        for ($i = 0; $i < 50; $i++) {
            $user    = $users->random();
            $billing = BillingInfo::where('user_id', $user->id)->first();

            if (!$billing) continue;

            $order = Order::create([
                'user_id'         => $user->id,
                'billing_info_id' => $billing->id,
                'order_number'    => 'ORD-' . str_pad($i + 1, 8, '0', STR_PAD_LEFT),
                'total'           => 0,
                'status'          => fake()->randomElement(['pending', 'paid', 'shipped', 'delivered', 'cancelled']),
                'payment_method'  => fake()->randomElement(['transferencia', 'efectivo', 'qr', 'deposito']),
            ]);

            $total       = 0;
            $numItems    = rand(1, 5);
            $usedProducts = [];

            for ($j = 0; $j < $numItems; $j++) {
                $available = $products->whereNotIn('id', $usedProducts);
                if ($available->isEmpty()) break;

                $product = $available->random();
                $qty     = rand(1, 3);
                $price   = $product->base_price;

                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_id'         => $product->id,
                    'quantity'           => $qty,
                    'price_when_ordered' => $price,
                ]);

                $total          += $price * $qty;
                $usedProducts[]  = $product->id;
            }

            $order->update(['total' => $total]);
        }

        $this->command->info('50 órdenes creadas con los productos actuales en BD.');
    }
}
