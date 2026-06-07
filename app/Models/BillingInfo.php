<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// centraliza los datos de facturacion y envio del usuario
// antes estos datos estaban mezclados en la tabla users y en cada pedido
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

    // un registro de facturacion pertenece a un usuario
    public function user(){
        return $this->belongsTo(User::class);
    }

    // los pedidos que usaron esta informacion de facturacion
    public function orders(){
        return $this->hasMany(Order::class);
    }

    // marca esta direccion como la predeterminada y quita el default a las demas del mismo usuario
    public function setAsDefault(): void{
        self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);
        $this->update(['is_default' => true]);
    }
}