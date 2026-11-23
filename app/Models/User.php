<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function objetosPerdidos(): HasMany
    {
        return $this->hasMany(ObjetoPerdido::class, 'usuario_id');
    }

    public function objetosEncontrados(): HasMany
    {
        return $this->hasMany(ObjetoEncontrado::class, 'usuario_id');
    }

    public function solicitudes(): HasMany
    {
        return $this->hasMany(
            SolicitudRecuperacion::class,
            'usuario_id'
        );
    }

    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'usuario_id');
    }
}