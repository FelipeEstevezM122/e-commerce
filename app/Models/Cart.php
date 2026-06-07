<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// representa el carrito de compras de un usuario
// cada usuario tiene un solo carrito permanente
class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id'
    ];

    // el carrito pertenece a un usuario
    public function user(){
        return $this->belongsTo(User::class);
    }

    // el carrito tiene muchos items (productos agregados)
    public function items(){
        return $this->hasMany(CartItem::class);
    }

    // suma el precio de todos los items para obtener el total del carrito
    public function getTotalAttribute(){
        return $this->items->sum(function ($item) {
            return $item->price_when_added * $item->quantity;
        });
    }

    // suma las cantidades de todos los items
    public function getTotalItemsAttribute(){
        return $this->items->sum('quantity');
    }

    // elimina todos los items del carrito
    public function clear(){
        $this->items()->delete();
    }

    // devuelve true si el carrito no tiene ningun item
    public function isEmpty(){
        return $this->items->isEmpty();
    }
}