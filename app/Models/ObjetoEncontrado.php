<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ObjetoEncontrado extends Model
{
    protected $table = 'objetos_encontrados';

    protected $fillable = [
        'usuario_id',
        'categoria_id',
        'titulo',
        'descripcion',
        'ubicacion',
        'fecha_encontrado',
        'imagen',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_encontrado' => 'date',
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
        return $this->hasMany(Coincidencia::class, 'objeto_encontrado_id');
    }

    public function solicitudes(): HasMany
    {
        return $this->hasMany(
            SolicitudRecuperacion::class,
            'objeto_encontrado_id'
        );
    }
}