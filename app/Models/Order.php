<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'order_number', //numero unico del pedido
        'total', //total a pagar
        'status', //pendiente, pagado, enviado, entregado, cancelado
        'payment_method', 
        'customer_whatsapp', //whatsapp del cliente
        'nit',
        'business_name', //razon social
        'shipping_address', //dirección de envio
    ];

    //Relacion: Un pedido pertenece a 1 usuario
    public function user(){
        return $this->belongsTo(User::class);
    }

    //Relacion: Un pedido tiene N items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    //Relacion: Un pedido tiene 1 ticket
    public function ticket(){
        return $this->hasOne(Ticket::class);
    }
    
    //Verificar si el pedido está pendiente
    public function isPending()
    {
        return $this->status === 'pending';
    }

    //Verificar si el pedido esta pagado
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    //Verificar si el pedido esta enviado
    public function isShipped()
    {
        return $this->status === 'shipped';
    }

    //Verificar si el pedido esta entregado
    public function isDelivered()
    {
        return $this->status === 'delivered';
    }

    //Verificar si el pedido esta cancelado
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    //Cambiar el estado del pedido
    public function changeStatus($newStatus)
    {
        $this->status = $newStatus;
        $this->save();
    }

    //Generar numero de pedido automáticamente
    public static function generateOrderNumber()
    {
        $last = self::latest('id')->first();
        $number = $last ? intval(substr($last->order_number, 4)) + 1 : 1;
        return 'ORD-' . str_pad($number, 8, '0', STR_PAD_LEFT);
    }
}