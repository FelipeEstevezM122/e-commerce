<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// guardamos el total de compras de un usuario por mes
// en lugar de guardar trimestre y anio como columnas, se calculan desde el campo month
class AccumulatedPurchase extends Model
{
    use HasFactory;

    protected $table = 'accumulated_purchases';

    protected $fillable = [
        'user_id',
        'month',         // primer dia del mes: 2025-01-01
        'total_purchases',
    ];

    protected $casts = [
        'month' => 'date',
    ];

    // devuelve el trimestre (1, 2, 3 o 4) calculado desde el campo month
    public function getQuarterAttribute(): int{
        return Carbon::parse($this->month)->quarter;
    }

    // devuelve el anio calculado desde el campo month
    public function getYearAttribute(): int{
        return Carbon::parse($this->month)->year;
    }

    // un registro de compras acumuladas pertenece a un usuario
    public function user(){
        return $this->belongsTo(User::class);
    }

    // verifica si el total del mes supera el minimo requerido (por defecto es 7000)
    public function meetsMonthlyRequirement(float $minimum = 7000): bool{
        return $this->total_purchases >= $minimum;
    }

    // suma un monto al total acumulado del mes
    public function addPurchase(float $amount): void{
        $this->increment('total_purchases', $amount);
    }

    // busca el registro del mes actual para un usuario especifico
    public static function getCurrentMonthPurchases(int $userId): ? self{
        return self::where('user_id', $userId)->where('month', now()->startOfMonth()->toDateString())->first();
    }
}