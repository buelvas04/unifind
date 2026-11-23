<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriasSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Documentos', 'descripcion' => 'Carnés, cédulas, licencias y otros documentos.'],
            ['nombre' => 'Dispositivos electrónicos', 'descripcion' => 'Celulares, computadores, tabletas y cargadores.'],
            ['nombre' => 'Útiles escolares', 'descripcion' => 'Cuadernos, libros, calculadoras y materiales académicos.'],
            ['nombre' => 'Accesorios personales', 'descripcion' => 'Llaves, gafas, relojes y otros accesorios.'],
            ['nombre' => 'Bolsos y mochilas', 'descripcion' => 'Maletines, morrales, bolsos y cartucheras.'],
            ['nombre' => 'Ropa', 'descripcion' => 'Chaquetas, gorras, uniformes y otras prendas.'],
            ['nombre' => 'Otros', 'descripcion' => 'Objetos que no pertenecen a las categorías anteriores.'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::firstOrCreate(
                ['nombre' => $categoria['nombre']],
                ['descripcion' => $categoria['descripcion']]
            );
        }
    }
}
