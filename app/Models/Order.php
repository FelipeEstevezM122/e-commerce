<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// representa un pedido realizado por un usuario
// los datos de facturacion ya no estan aqui, estan en billing_info
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'billing_info_id',
        'order_number',
        'total',
        'status',
        'payment_method',
    ];

    // el pedido pertenece a un usuario
    public function user(){
        return $this->belongsTo(User::class);
    }

    // el pedido usa una direccion de facturacion guardada
    public function billingInfo(){
        return $this->belongsTo(BillingInfo::class);
    }

    // el pedido tiene muchos items (productos comprados)
    public function items(){
        return $this->hasMany(OrderItem::class);
    }

    // el pedido puede tener un ticket generado
    public function ticket(){
        return $this->hasOne(Ticket::class);
    }

    // helpers para saber el estado actual del pedido
    public function isPending(): bool{return $this->status === 'pending';}
    public function isPaid(): bool{return $this->status === 'paid';}
    public function isShipped(): bool{return $this->status === 'shipped';}
    public function isDelivered(): bool{return $this->status === 'delivered';}
    public function isCancelled(): bool{return $this->status === 'cancelled';}

    // cambia el estado del pedido
    public function changeStatus(string $newStatus): void{
        $this->update(['status' => $newStatus]);
    }

    // genera un numero de pedido unico tipo ORD-00000001
    public static function generateOrderNumber(): string{
        $last   = self::latest('id')->first();
        $number = $last ? intval(substr($last->order_number, 4)) + 1 : 1;
        return 'ORD-' . str_pad($number, 8, '0', STR_PAD_LEFT);
    }
}