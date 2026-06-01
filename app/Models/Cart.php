<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id' //duenio del carrito
    ];

    //Relacion: Un carrito pertenece a 1 usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Relacion: Un carrito tiene N items (productos)
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    //Calcular el total del carrito
    public function getTotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->price_when_added * $item->quantity;
        });
    }

    //Calcular la cantidad total de productos en el carrito
    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

    //Vaciar el carrito (eliminar todos los items)
    public function clear()
    {
        $this->items()->delete();
    }

    //Verificar si el carrito está vacio
    public function isEmpty()
    {
        return $this->items->isEmpty();
    }
}
