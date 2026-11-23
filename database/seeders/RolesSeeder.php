<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Estudiante',
            'Profesor',
            'Administrador',
        ];

        foreach ($roles as $nombre) {
            Rol::firstOrCreate([
                'nombre' => $nombre,
            ]);
        }
    }
}