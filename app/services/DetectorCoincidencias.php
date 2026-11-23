<?php

namespace App\Services;

use App\Models\Coincidencia;
use App\Models\Notificacion;
use App\Models\ObjetoEncontrado;
use App\Models\ObjetoPerdido;

class DetectorCoincidencias
{
    public function detectarParaEncontrado(ObjetoEncontrado $encontrado): void
    {
        if ($encontrado->estado !== 'disponible') {
            return;
        }

        $perdidos = ObjetoPerdido::query()
            ->where('estado', 'perdido')
            ->where('categoria_id', $encontrado->categoria_id)
            ->where('usuario_id', '!=', $encontrado->usuario_id)
            ->get();

        foreach ($perdidos as $perdido) {
            $this->evaluar($perdido, $encontrado);
        }
    }

    public function detectarParaPerdido(ObjetoPerdido $perdido): void
    {
        if ($perdido->estado !== 'perdido') {
            return;
        }

        $encontrados = ObjetoEncontrado::query()
            ->where('estado', 'disponible')
            ->where('categoria_id', $perdido->categoria_id)
            ->where('usuario_id', '!=', $perdido->usuario_id)
            ->get();

        foreach ($encontrados as $encontrado) {
            $this->evaluar($perdido, $encontrado);
        }
    }

    private function evaluar(
        ObjetoPerdido $perdido,
        ObjetoEncontrado $encontrado
    ): void {
        $nivel = $this->calcularNivel($perdido, $encontrado);

        // Umbral provisional: 60 puntos sobre 100.
        if ($nivel < 60) {
            return;
        }

        $coincidencia = Coincidencia::firstOrCreate(
            [
                'objeto_perdido_id' => $perdido->id,
                'objeto_encontrado_id' => $encontrado->id,
            ],
            [
                'nivel_coincidencia' => $nivel,
            ]
        );

        
if ($coincidencia->wasRecentlyCreated) {
    Notificacion::create([
        'usuario_id' => $perdido->usuario_id,
        'objeto_encontrado_id' => $encontrado->id,
        'mensaje' => 'Posible coincidencia: encontramos un objeto que podría corresponder a tu reporte de '.$perdido->titulo.'. Revisa los objetos encontrados.',
        'estado' => 'no_leido'
    ]);
}

    }

    private function calcularNivel(
        ObjetoPerdido $perdido,
        ObjetoEncontrado $encontrado
    ): float {
        $nivel = 40;

        $tituloPerdido = $this->normalizar($perdido->titulo);
        $tituloEncontrado = $this->normalizar($encontrado->titulo);

        similar_text(
            $tituloPerdido,
            $tituloEncontrado,
            $similitudTitulo
        );

        $nivel += ($similitudTitulo / 100) * 25;

        $descripcionPerdido = $this->normalizar($perdido->descripcion);
        $descripcionEncontrado = $this->normalizar($encontrado->descripcion);

        similar_text(
            $descripcionPerdido,
            $descripcionEncontrado,
            $similitudDescripcion
        );

        $nivel += ($similitudDescripcion / 100) * 20;

        $lugarPerdido = $this->normalizar($perdido->lugar_perdida);
        $lugarEncontrado = $this->normalizar($encontrado->ubicacion);

        similar_text(
            $lugarPerdido,
            $lugarEncontrado,
            $similitudLugar
        );

        $nivel += ($similitudLugar / 100) * 15;

        return round(min($nivel, 100), 2);
    }

    private function normalizar(?string $texto): string
    {
        $texto = mb_strtolower(trim($texto ?? ''), 'UTF-8');

        $texto = preg_replace('/\s+/u', ' ', $texto);

        return $texto ?? '';
    }
}
