<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// representa un nivel de cliente: bronce, plata, oro, platino
// el rango sube segun cuanto compra el usuario al mes
class Rank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'monthly_minimum_purchase',
        'description',
    ];

    // un rango puede tener muchos usuarios
    public function users(){
        return $this->hasMany(User::class);
    }

    // verifica si un monto de compras cumple el minimo para este rango
    public function qualifies($totalPurchases){
        return $totalPurchases >= $this->monthly_minimum_purchase;
    }
}