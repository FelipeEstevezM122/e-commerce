<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'ticket_number', //numero unico del ticket
        'issued_at'// fecha de emisión
    ];

    protected $casts = [
        'issued_at' => 'datetime'
    ];

    //Relacion: Un ticket pertenece a 1 pedido
    public function order(){
        return $this->belongsTo(Order::class);
    }

    //Generar un numero de ticket automaticamente
    public static function generateTicketNumber()
    {
        $last = self::latest('id')->first();
        $number = $last ? intval(substr($last->ticket_number, 4)) + 1 : 1;
        return 'TKT-' . str_pad($number, 8, '0', STR_PAD_LEFT);
    }
}
