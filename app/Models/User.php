<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'whatsapp',
        'address',
        'company_name',
        'user_type', // tipo: final, mayorista
        'access_code', // codigo de acceso (de 6 digitos)
        'rank_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relacion: Un usuario tiene un rango
    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    // Relacion: un usuario tiene muchos roles (muchos a muchos)
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role')
                    ->withPivot('assigned_at')
                    ->withTimestamps();
    }

    // Relacion: Un usuario tiene 1 carrito
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    // Relacion: Un usuario tiene muchos pedidos
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relacion: Un usuario tiene muchas compras acumuladas
    public function accumulatedPurchases()
    {
        return $this->hasMany(AccumulatedPurchase::class);
    }

    // Verificar si es administrador
    public function isAdmin()
    {
        return $this->roles()->where('name', 'admin')->exists();
    }

    // Verificar si es mayorista
    public function isWholesaler()
    {
        return $this->user_type === 'mayorista';
    }

    // Verificar si es cliente final
    public function isFinalCustomer()
    {
        return $this->user_type === 'final';
    }

    /**
     * Precio final según el tipo de usuario
     * Mayorista: precio base × 0.90 (10% descuento)
     * Cliente final: precio base
     */
    public function getFinalPrice($basePrice)
    {
        if ($this->isWholesaler()) {
            return $basePrice * 0.90; // 10% descuento
        }
        return $basePrice;
    }
}