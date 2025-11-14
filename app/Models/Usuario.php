<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'correo',
        'password',
        'rol_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'usuario_id');
    }
}
