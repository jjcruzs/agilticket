<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Tabla personalizada
    protected $table = 'usuarios';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'correo',
        'password',
        'rol_id',
    ];

    // Campos ocultos
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Timestamps
    public $timestamps = true;

    // Relación con roles
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    // Campo de login
    public function getAuthIdentifierName()
    {
        return 'correo';
    }

    // ✅ No encriptamos aquí para evitar doble hash
}
