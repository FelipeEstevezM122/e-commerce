<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id', //id de su carrito
        'product_id', //producto
        'quantity', //cantidad del producto
        'price_when_added' //precio del producto
    ];

    //Relacion: Un item pertenece a 1 carrito
    public function cart(){
        return $this->belongsTo(Cart::class);
    }

    //Relacion: Un item pertenece a 1 producto
    public function product(){
        return $this->belongsTo(Product::class);
    }

    //Total del item (precio * cantidad)
    public function getTotalAttribute(){
        return $this->price_when_added * $this->quantity;
    }
}
