<?php

namespace App\Models;

use App\Models\ObjetoEncontrado;
use App\Models\ObjetoPerdido;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudRecuperacion extends Model
{
    protected $table = 'solicitudes_recuperacion';

    protected $fillable = [
        'usuario_id',
        'objeto_encontrado_id',
        'objeto_perdido_id',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'confirmacion_entrega' => 'boolean',
            'confirmacion_recepcion' => 'boolean',
            'fecha_confirmacion_entrega' => 'datetime',
            'fecha_confirmacion_recepcion' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function objetoEncontrado(): BelongsTo
    {
        return $this->belongsTo(
            ObjetoEncontrado::class,
            'objeto_encontrado_id'
        );
    }

    public function objetoPerdido(): BelongsTo
    {
        return $this->belongsTo(
            ObjetoPerdido::class,
            'objeto_perdido_id'
        );
    }
}
