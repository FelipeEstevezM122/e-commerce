<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// representa un producto dentro del carrito
// guarda el precio en el momento en que se agrego para que no cambie si el producto cambia de precio
class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price_when_added'
    ];

    // el item pertenece a un carrito
    public function cart(){
        return $this->belongsTo(Cart::class);
    }

    // el item pertenece a un producto
    public function product(){
        return $this->belongsTo(Product::class);
    }

    // devuelve el subtotal de este item (precio por cantidad)
    public function getTotalAttribute(){
        return $this->price_when_added * $this->quantity;
    }
}