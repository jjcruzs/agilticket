<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';
    public $timestamps = true;

    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'radicado',
        'titulo',
        'descripcion',
        'prioridad',
        'estado_id',
        'solicitante_id',
        'responsable_id',
        'radicado',
    ];

    protected static function booted()
    {
        static::creating(function ($ticket) {
            if (empty($ticket->radicado)) {
                $ultimo = self::max('id') ?? 0;
                $nuevoNumero = str_pad($ultimo + 1, 4, '0', STR_PAD_LEFT);
                $ticket->radicado = 'TCK-' . $nuevoNumero;
            }
        });
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'ticket_id');
    }

    public function solicitante()
    {
        return $this->belongsTo(Usuario::class, 'solicitante_id');
    }

    public function responsable()
    {
        return $this->belongsTo(Usuario::class, 'responsable_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }
}
