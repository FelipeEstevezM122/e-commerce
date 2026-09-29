<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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