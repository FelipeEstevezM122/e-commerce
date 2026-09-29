<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// representa a un usuario del sistema
// puede ser cliente, mayorista o admin segun sus roles
// los datos de direccion y empresa ya no estan aqui, estan en billing_info
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'whatsapp',
        'access_code',
        'rank_id',
        // se elimino user_type porque ahora se usan roles
        // se elimino address porque se movio a billing_info
        // se elimino company_name porque se movio a billing_info
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // el usuario tiene un rango asignado (bronce, plata, oro, platino)
    public function rank(){
        return $this->belongsTo(Rank::class);
    }

    // el usuario puede tener varios roles (muchos a muchos con tabla user_role)
    public function roles(){
        return $this->belongsToMany(Role::class, 'user_role')
                    ->withPivot('assigned_at')
                    ->withTimestamps();
    }

    // el usuario tiene un solo carrito permanente
    public function cart(){
        return $this->hasOne(Cart::class);
    }

    // el usuario puede tener muchos pedidos
    public function orders(){
        return $this->hasMany(Order::class);
    }

    // historial de compras acumuladas mes a mes
    public function accumulatedPurchases(){
        return $this->hasMany(AccumulatedPurchase::class);
    }

    // el usuario puede tener varias direcciones de facturacion guardadas
    public function billingInfo(){
        return $this->hasMany(BillingInfo::class);
    }

    // devuelve solo la direccion marcada como predeterminada
    public function defaultBillingInfo(){
        return $this->hasOne(BillingInfo::class)->where('is_default', true);
    }

    // verifica si el usuario tiene un rol especifico
    public function hasRole(string $role): bool{
        return $this->roles()->where('name', $role)->exists();
    }

    // verifica si el usuario es administrador
    public function isAdmin(): bool{
        return $this->hasRole('admin');
    }

    // verifica si el usuario es mayorista
    public function isWholesaler(): bool{
        return $this->hasRole('mayorista');
    }

    // verifica si el usuario es cliente final
    public function isFinalCustomer(): bool{
        return $this->hasRole('cliente');
    }

    // los mayoristas tienen 10% de descuento sobre el precio base
    public function getFinalPrice($basePrice): float{
        return $this->isWholesaler() ? $basePrice * 0.90 : $basePrice;
    }
}