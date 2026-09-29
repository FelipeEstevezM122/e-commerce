<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartSeeder extends Seeder
{
    public function run()
    {
        $users    = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->info('Primero crea usuarios y productos.');
            return;
        }

        foreach ($users as $user) {
            // Cada usuario tiene 1 solo carrito (unique en la migración)
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            // Entre 0 y 4 items en el carrito (algunos carritos vacíos es realista)
            $numItems = rand(0, 4);
            $productosAgregados = [];

            for ($i = 0; $i < $numItems; $i++) {
                // Evitar el mismo producto dos veces en el mismo carrito
                $product = $products->whereNotIn('id', $productosAgregados)->random();

                if (!$product) continue;

                CartItem::create([
                    'cart_id'          => $cart->id,
                    'product_id'       => $product->id,
                    'quantity'         => rand(1, 5),
                    'price_when_added' => $product->base_price,
                ]);

                $productosAgregados[] = $product->id;
            }
        }

        $this->command->info('Carritos creados: ' . $users->count());
    }
}
