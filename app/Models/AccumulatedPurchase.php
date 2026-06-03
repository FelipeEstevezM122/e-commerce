<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\\Eloquent\Model;

class AccumulatedPurchase extends Model
{
    use HasFactory;

    protected $table = 'accumulated_purchases';

    protected $fillable = [
        'user_id',
        'month',         // primer día del mes: 2025-01-01
        'total_purchases',
        // ELIMINADO: quarter → usar getQuarterAttribute()
        // ELIMINADO: year    → usar getYearAttribute()
    ];

    protected $casts = [
        'month' => 'date',
    ];

    // ─── Accessors (calculados desde month) ──────────────────

    // Uso: $record->quarter → 1, 2, 3 o 4
    public function getQuarterAttribute(): int
    {
        return Carbon::parse($this->month)->quarter;
    }

    // Uso: $record->year → 2025
    public function getYearAttribute(): int
    {
        return Carbon::parse($this->month)->year;
    }

    // ─── Relaciones ──────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers ─────────────────────────────────────────────

    public function meetsMonthlyRequirement(float $minimum = 7000): bool
    {
        return $this->total_purchases >= $minimum;
    }

    public function addPurchase(float $amount): void
    {
        $this->increment('total_purchases', $amount);
    }

    public static function getCurrentMonthPurchases(int $userId): ?self
    {
        return self::where('user_id', $userId)
                   ->where('month', now()->startOfMonth()->toDateString())
                   ->first();
    }
}
