<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

// representa el comprobante asociado a un pedido
class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'ticket_number',
        'issued_at'
    ];

    protected $casts = [
        'issued_at' => 'datetime'
    ];

    // el ticket pertenece a un pedido
    public function order(){
        return $this->belongsTo(Order::class);
    }

    // Usa un identificador aleatorio para evitar colisiones en compras simultaneas.
    public static function generateTicketNumber(): string
    {
        return 'TKT-' . now()->format('Ymd') . '-' . Str::upper(Str::random(12));
    }

    public static function createForOrder(Order $order): self
    {
        return $order->ticket()->firstOrCreate([], [
            'ticket_number' => self::generateTicketNumber(),
            'issued_at' => now(),
        ]);
    }
}