<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity', //cantidad del producto
        'price_when_ordered' //precio del producto
    ];

    //Relacion: Un item pertenece a un pedido
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    
    //Relacion: Un item pertenece a un producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    //Calcular el subtotal del item (precio × cantidad)
    public function getSubtotalAttribute()
    {
        return $this->price_when_ordered * $this->quantity;
    }
}