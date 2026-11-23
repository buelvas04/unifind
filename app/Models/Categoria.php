<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function objetosPerdidos(): HasMany
    {
        return $this->hasMany(ObjetoPerdido::class, 'categoria_id');
    }

    public function objetosEncontrados(): HasMany
    {
        return $this->hasMany(ObjetoEncontrado::class, 'categoria_id');
    }
}