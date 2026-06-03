<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// MODELO NUEVO: centraliza datos de facturación y envío del usuario
// Antes estaban dispersos en: users (address, company_name) y orders (nit, business_name, shipping_address, customer_whatsapp)
class BillingInfo extends Model
{
    use HasFactory;

    protected $table = 'billing_info';

    protected $fillable = [
        'user_id',
        'address',
        'company_name',
        'nit',
        'business_name',
        'whatsapp',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // Un billing_info pertenece a 1 usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Los pedidos que usaron este billing_info
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Marcar como dirección predeterminada
    public function setAsDefault(): void
    {
        // Quitar default a las demás del mismo usuario
        self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        $this->update(['is_default' => true]);
    }
}
