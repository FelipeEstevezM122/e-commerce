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
        'access_code',
        'rank_id',
        // ELIMINADO: user_type    → usar hasRole('mayorista')
        // ELIMINADO: address      → usar billingInfo()
        // ELIMINADO: company_name → usar billingInfo()
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ─── Relaciones ───────────────────────────────────────────

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role')
                    ->withPivot('assigned_at')
                    ->withTimestamps();
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function accumulatedPurchases()
    {
        return $this->hasMany(AccumulatedPurchase::class);
    }

    // NUEVO: datos de facturación separados del usuario
    public function billingInfo()
    {
        return $this->hasMany(BillingInfo::class);
    }

    public function defaultBillingInfo()
    {
        return $this->hasOne(BillingInfo::class)->where('is_default', true);
    }

    // ─── Helpers de rol (reemplazan user_type) ────────────────

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    // REEMPLAZA: $user->user_type === 'mayorista'
    public function isWholesaler(): bool
    {
        return $this->hasRole('mayorista');
    }

    // REEMPLAZA: $user->user_type === 'final'
    public function isFinalCustomer(): bool
    {
        return $this->hasRole('cliente');
    }

    public function getFinalPrice($basePrice): float
    {
        return $this->isWholesaler() ? $basePrice * 0.90 : $basePrice;
    }
}
