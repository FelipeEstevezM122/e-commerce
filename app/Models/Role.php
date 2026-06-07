<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// representa un rol del sistema: cliente, mayorista o admin
// un usuario puede tener varios roles al mismo tiempo
class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // un rol puede pertenecer a muchos usuarios (relacion muchos a muchos)
    // la tabla intermedia es user_role y guarda la fecha en que se asigno el rol
    public function users(){
        return $this->belongsToMany(User::class, 'user_role')->withPivot('assigned_at')->withTimestamps();
    }
}