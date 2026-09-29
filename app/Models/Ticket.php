<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// representa el comprobante de un pedido entregado
// se genera automaticamente cuando el pedido pasa a estado delivered
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

    // genera un numero de ticket unico tipo TKT-00000001
    public static function generateTicketNumber(){
        $last = self::latest('id')->first();
        $number = $last ? intval(substr($last->ticket_number, 4)) + 1 : 1;
        return 'TKT-' . str_pad($number, 8, '0', STR_PAD_LEFT);
    }
}