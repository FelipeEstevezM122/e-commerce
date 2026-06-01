<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'monthly_minimum_purchase', //Compra minima mensual
        'description',
    ];

    //Relacion: Un rango esta en N usuarios
    public function users(){
        return $this->hasMany(User::class);
    }

    //Verificar la compra minima
    public function qualifies($totalPurchases){
        return $totalPurchases >= $this->monthly_minimum_purchase;
    }
}
