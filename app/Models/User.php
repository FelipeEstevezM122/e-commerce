<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\PurchaseAccumulated;
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

    //Relacion: Un usuario tiene un rango
    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    //Relacion: un rol pertenece a N usuarios
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role')
                    ->withPivot('assigned_at')
                    ->withTimestamps();
    }

    //Relacion: Un usuario tiene 1 carrito
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    //Relacion: Un usuario tiene n pedidos
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    //Relacion: Un usuario realiza N compras
    public function accumulatedPurchases()
    {
        return $this->hasMany(AccumulatedPurchase::class);
    }
}
