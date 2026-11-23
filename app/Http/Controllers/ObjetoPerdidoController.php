<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\ObjetoPerdido;
use App\Services\DetectorCoincidencias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ObjetoPerdidoController extends Controller
{
    public function index(Request $request)
    {
        $objetos = ObjetoPerdido::query()
            ->where('usuario_id', $request->user()->id)
            ->with('categoria')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('objetos-perdidos.index', compact('objetos'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();

        return view('objetos-perdidos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string', 'max:2000'],
            'categoria_id' => [
                'required',
                Rule::exists('categorias', 'id'),
            ],
            'lugar_perdida' => ['required', 'string', 'max:255'],
            'fecha_perdida' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'imagen' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $rutaImagen = null;

        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')
                ->store('objetos-perdidos', 'public');
        }

        try {
            $objeto = new ObjetoPerdido();

            $objeto->usuario_id = $request->user()->id;
            $objeto->categoria_id = $datos['categoria_id'];
            $objeto->titulo = $datos['titulo'];
            $objeto->descripcion = $datos['descripcion'];
            $objeto->lugar_perdida = $datos['lugar_perdida'];
            $objeto->fecha_perdida = $datos['fecha_perdida'];
            $objeto->imagen = $rutaImagen;
            $objeto->estado = 'perdido';

            $objeto->save();

        } catch (\Throwable $e) {
            if ($rutaImagen !== null) {
                Storage::disk('public')->delete($rutaImagen);
            }

            throw $e;
        }

        app(DetectorCoincidencias::class)
            ->detectarParaPerdido($objeto);

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                'El objeto perdido fue registrado correctamente.'
            );
    }
}
