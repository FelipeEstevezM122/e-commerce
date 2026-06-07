<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// representa una marca de producto (dahua, hikvision, etc)
class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'logo',
    ];

    // una marca puede tener muchos productos
    public function products(){
        return $this->hasMany(Product::class);
    }
}