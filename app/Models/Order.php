<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'billing_info_id', // NUEVO: FK a billing_info (reemplaza nit, business_name, shipping_address, customer_whatsapp)
        'order_number',
        'total',
        'status',
        'payment_method',
    ];

    // ─── Relaciones ───────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // NUEVO: acceso directo a datos de facturación
    public function billingInfo()
    {
        return $this->belongsTo(BillingInfo::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class);
    }

    // ─── Helpers de estado ────────────────────────────────────

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isPaid(): bool      { return $this->status === 'paid'; }
    public function isShipped(): bool   { return $this->status === 'shipped'; }
    public function isDelivered(): bool { return $this->status === 'delivered'; }
    public function isCancelled(): bool { return $this->status === 'cancelled'; }

    public function changeStatus(string $newStatus): void
    {
        $this->update(['status' => $newStatus]);
    }

    public static function generateOrderNumber(): string
    {
        $last   = self::latest('id')->first();
        $number = $last ? intval(substr($last->order_number, 4)) + 1 : 1;
        return 'ORD-' . str_pad($number, 8, '0', STR_PAD_LEFT);
    }
}
