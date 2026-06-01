<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccumulatedPurchase extends Model
{
    use HasFactory;

    protected $table = 'accumulated_purchases';

    protected $fillable = [
        'user_id',
        'month',
        'quarter', //trimestre de la compra acumulada
        'year', //anio de la compra acumulada
        'total_purchases' //total comprado en el periodo
    ];

    protected $casts = [
        'month' => 'date',
        'quarter' => 'date',
        'year' => 'date'
    ];

    //Relacion: Una compra acumulada pertenece a 1 usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Verificar si el mayorista cumple con la compra mínima mensual
    //Requisito: 7000 Bs por mes
    public function meetsMonthlyRequirement($minimum = 7000)
    {
        return $this->total_purchases >= $minimum;
    }

    //Sumar compras a los acumulados
    public function addPurchase($amount)
    {
        $this->total_purchases += $amount;
        $this->save();
    }

    //Obtener compras del mes actual para un usuario
    public static function getCurrentMonthPurchases($userId)
    {
        $currentMonth = now()->startOfMonth();
        
        return self::where('user_id', $userId)
                    ->where('month', $currentMonth)
                    ->first();
    }
}
