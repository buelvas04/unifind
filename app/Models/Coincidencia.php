<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coincidencia extends Model
{
    protected $table = 'coincidencias';

    protected $fillable = [
        'objeto_perdido_id',
        'objeto_encontrado_id',
        'nivel_coincidencia',
    ];

    protected function casts(): array
    {
        return [
            'nivel_coincidencia' => 'decimal:2',
        ];
    }

    public function objetoPerdido(): BelongsTo
    {
        return $this->belongsTo(
            ObjetoPerdido::class,
            'objeto_perdido_id'
        );
    }

    public function objetoEncontrado(): BelongsTo
    {
        return $this->belongsTo(
            ObjetoEncontrado::class,
            'objeto_encontrado_id'
        );
    }
}