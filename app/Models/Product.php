<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'base_price',
        'stock',
        'image', // ruta de la imagen
        'sku', //codigo unico del producto
        'warranty_days', //dias de garantia
        'brand_id', //marca
        'category_id'
    ];

    //Relacion: Un producto tiene 1 marca
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    
    //Relacion: Un producto tiene 1 categoria
    public function category(){
        return $this->belongsTo(Category::class);
    }

    //Relacion: Un producto tiene n items en el carrito
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    //Relacion: Un producto tienen n items en pedidos
    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
    
    //Stock
    public function hasStock($quantity){
        return $this->stock >= $quantity;
    }

    //Quitamos el stock
    public function decrementStock($quantity){
        $this->decrement('stock', $quantity);
    }

    //Aumentar stock
    public function incrementStock($quantity){
        $this->increment('stock', $quantity);
    }
}
