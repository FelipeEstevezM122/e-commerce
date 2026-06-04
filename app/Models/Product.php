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
        'image1',
        'image2',
        'image3',
        'image4',
        'sku',
        'warranty_days',
        'brand_id',
        'category_id',
    ];

    // Relaciones
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Stock helpers
    public function hasStock($quantity)
    {
        return $this->stock >= $quantity;
    }

    public function decrementStock($quantity)
    {
        $this->decrement('stock', $quantity);
    }

    public function incrementStock($quantity)
    {
        $this->increment('stock', $quantity);
    }

    // Devuelve solo las imagenes que tienen valor
    public function getImagesAttribute(): array
    {
        return array_values(array_filter([
            $this->image1,
            $this->image2,
            $this->image3,
            $this->image4,
        ]));
    }
}