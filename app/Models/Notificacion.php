<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = [
        'usuario_id',
        'objeto_encontrado_id',
        'mensaje',
        'estado',
    ];

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
}
