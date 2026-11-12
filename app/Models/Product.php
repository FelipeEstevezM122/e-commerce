<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

// representa un producto del catalogo
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'base_price',
        'stock',
        'image1',
        'image2',
        'image3',
        'image4',
        'sku',
        'warranty_days',
        'brand_id',
        'category_id',
    ];


    // HISTORIAL DE PRECIOS (HU-10)
    // Se guarda automaticamente cada vez que cambia base_price, sin importar
    // desde donde se actualice (controlador, tinker, seeder, etc.).
    protected static function booted(): void
    {
        static::updating(function (Product $product) {
            if (!$product->isDirty('base_price')) {
                return;
            }
            $old = (float) $product->getOriginal('base_price');
            $new = (float) $product->base_price;
            if ($old === $new) {
                return;
            }
            // Si la tabla no existe NO fallamos en silencio: avisamos en storage/logs/laravel.log
            if (!Schema::hasTable('product_price_history')) {
                Log::warning('product_price_history no existe: ejecute "php artisan migrate". Precio no registrado en historial.', ['product_id' => $product->id]);
                return;
            }
            ProductPriceHistory::create([
                'product_id' => $product->id,
                'old_price'  => $old,
                'new_price'  => $new,
                'changed_by' => Auth::id(),   // null si el cambio no viene de un usuario logueado
                'changed_at' => now(),
            ]);
        });
    }

    // el producto pertenece a una marca
    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    // el producto pertenece a una categoria
    public function category(){
        return $this->belongsTo(Category::class);
    }

    // el producto puede estar en muchos carritos
    public function cartItems(){
        return $this->hasMany(CartItem::class);
    }

    // el producto puede estar en muchos pedidos
    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }

    //hitorial de cambios de precio del producto
    public function priceHistory(){
        return $this->hasMany(ProductPriceHistory::class)->orderBy('changed_at', 'desc');
    }

    // verifica si hay suficiente stock para una cantidad pedida
    public function hasStock($quantity){
        return $this->stock >= $quantity;
    }

    // resta stock cuando se vende
    public function decrementStock($quantity){
        $this->decrement('stock', $quantity);
    }

    // suma stock cuando se cancela o se repone
    public function incrementStock($quantity){
        $this->increment('stock', $quantity);
    }

    // devuelve solo las imagenes que tienen valor, sin nulls
    public function getImagesAttribute(): array{
        return array_values(array_filter([
            $this->image1,
            $this->image2,
            $this->image3,
            $this->image4,
        ]));
    }
}