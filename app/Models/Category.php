<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// representa una categoria de producto (camaras, alarmas, redes, etc)
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // una categoria puede tener muchos productos
    public function products(){
        return $this->hasMany(Product::class);
    }
}