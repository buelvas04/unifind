<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mensaje extends Model
{
    protected $table = 'mensajes';

    protected $fillable = [
        'solicitud_recuperacion_id',
        'usuario_id',
        'contenido',
    ];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(
            SolicitudRecuperacion::class,
            'solicitud_recuperacion_id'
        );
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'usuario_id'
        );
    }
}
