<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ObjetoPerdido extends Model
{
    protected $table = 'objetos_perdidos';

    protected $fillable = [
        'usuario_id',
        'categoria_id',
        'titulo',
        'descripcion',
        'lugar_perdida',
        'fecha_perdida',
        'imagen',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_perdida' => 'date',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function coincidencias(): HasMany
    {
        return $this->hasMany(Coincidencia::class, 'objeto_perdido_id');
    }
}