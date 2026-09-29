<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// representa un producto dentro de un pedido
// guarda el precio al momento de la compra para que no cambie aunque el producto cambie despues
class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price_when_ordered'
    ];

    // el item pertenece a un pedido
    public function order(){
        return $this->belongsTo(Order::class);
    }

    // el item pertenece a un producto
    public function product(){
        return $this->belongsTo(Product::class);
    }

    // devuelve el subtotal de este item (precio por cantidad)
    public function getSubtotalAttribute(){
        return $this->price_when_ordered * $this->quantity;
    }
}