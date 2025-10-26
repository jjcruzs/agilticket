<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';
    public $timestamps = false; // ✅ porque tu tabla usa 'fecha_creacion' y no 'created_at'

    protected $fillable = [
        'titulo',
        'descripcion',
        'prioridad',
        'estado_id',
        'solicitante_id',
        'responsable_id',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function solicitante()
    {
        return $this->belongsTo(Usuario::class, 'solicitante_id');
    }

    public function responsable()
    {
        return $this->belongsTo(Usuario::class, 'responsable_id');
    }
}

