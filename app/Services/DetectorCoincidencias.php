<?php

namespace App\Services;

use App\Models\Coincidencia;
use App\Models\Notificacion;
use App\Models\ObjetoEncontrado;
use App\Models\ObjetoPerdido;
use Illuminate\Support\Str;

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

    /**
     * Compara palabras en lugar de caracteres: ignora mayúsculas, tildes,
     * orden de palabras y pequeñas faltas de ortografía.
     *
     * Conservamos la valoración original: categoría (40), título (25),
     * descripción (20), lugar (15) y umbral mínimo (60).
     */
    private function calcularNivel(
        ObjetoPerdido $perdido,
        ObjetoEncontrado $encontrado
    ): float {
        $titulo = $this->similitudPalabras($perdido->titulo, $encontrado->titulo, true);

        // Sin ninguna palabra en común en el título no avisamos:
        // la coincidencia de categoría o lugar no basta por sí sola.
        if ($titulo == 0.0) {
            return 0.0;
        }

        $descripcion = $this->similitudPalabras(
            $perdido->descripcion,
            $encontrado->descripcion
        );

        $lugar = $this->similitudPalabras(
            $perdido->lugar_perdida,
            $encontrado->ubicacion
        );

        $nivel = 40
            + ($titulo / 100) * 25
            + ($descripcion / 100) * 20
            + ($lugar / 100) * 15;

        return round(min($nivel, 100), 2);
    }

    /**
     * Para títulos usamos el número de palabras del título más corto.
     * Así "calculadora casio seminueva negra" y
     * "calculadora cassio seminueva" coinciden aunque falte el color.
     */
    private function similitudPalabras(
        ?string $textoA,
        ?string $textoB,
        bool $esTitulo = false
    ): float {
        $a = $this->extraerPalabras($textoA);
        $b = $this->extraerPalabras($textoB);

        if (empty($a) || empty($b)) {
            return 0.0;
        }

        // Primero emparejamos palabras exactamente iguales.
        $exactas = array_intersect($a, $b);
        $coincidencias = count($exactas);

        // Después solo comparamos las palabras restantes,
        // sin utilizar una misma palabra más de una vez.
        $restantesA = array_values(array_diff($a, $exactas));
        $restantesB = array_values(array_diff($b, $exactas));

        foreach ($restantesA as $palabraA) {
            foreach ($restantesB as $indice => $palabraB) {
                if ($this->equivalentes($palabraA, $palabraB)) {
                    $coincidencias++;
                    unset($restantesB[$indice]);
                    break;
                }
            }
        }

        $denominador = $esTitulo
            ? min(count($a), count($b))
            : (count($a) + count($b)) / 2;

        return 100 * $coincidencias / $denominador;
    }

    /**
     * Casio/Cassio: una letra agregada.
     * seminueva/seminuebo: hasta dos cambios para palabras largas.
     * Los números (modelos de equipos) sí deben coincidir exactamente.
     */
    private function equivalentes(string $a, string $b): bool
    {
        if ($a === $b) {
            return true;
        }

        if (preg_match('/[0-9]/', $a . $b)) {
            return false;
        }

        $minimo = min(strlen($a), strlen($b));
        $maximo = max(strlen($a), strlen($b));

        if ($minimo < 4) {
            return false;
        }

        $distancia = levenshtein($a, $b);
        $maxCambios = $minimo >= 8 ? 2 : 1;

        return $distancia <= $maxCambios
            && ($distancia / $maximo) <= 0.23;
    }

    private function extraerPalabras(?string $texto): array
    {
        $texto = Str::ascii(mb_strtolower(trim($texto ?? ''), 'UTF-8'));

        $palabras = preg_split(
            '/[^a-z0-9]+/',
            $texto,
            -1,
            PREG_SPLIT_NO_EMPTY
        ) ?: [];

        // Artículos y conectores no identifican el objeto.
        $omitidas = [
            'el', 'la', 'los', 'las', 'de', 'del', 'al', 'en',
            'un', 'una', 'unos', 'unas', 'y', 'con', 'sin',
            'por', 'para', 'que', 'se', 'es'
        ];

        $palabras = array_filter(
            $palabras,
            static fn (string $palabra): bool =>
                strlen($palabra) >= 2
                && !in_array($palabra, $omitidas, true)
        );

        return array_values(array_unique($palabras));
    }
}
