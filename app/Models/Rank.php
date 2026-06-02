<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'monthly_minimum_purchase',
        'description',
    ];

    // Relacion: Un rango tiene muchos usuarios
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Verificar si un usuario cumple con la compra minima
    public function qualifies($totalPurchases)
    {
        return $totalPurchases >= $this->monthly_minimum_purchase;
    }
}