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
    ];

    // ==========================
    // 🔹 GENERAR RADICADO AUTO (TCK-0001, TCK-0002, etc.)
    // ==========================
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $ultimo = self::latest('id')->first();
            $numero = $ultimo ? $ultimo->id + 1 : 1;
            $ticket->radicado = 'TCK-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
        });
    }

    // ==========================
    // 🔹 RELACIONES
    // ==========================

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
