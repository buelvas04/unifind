<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\ObjetoEncontrado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ObjetoEncontradoController extends Controller
{
    
public function show(Request $request, ObjetoEncontrado $objeto)
{
    if (
        $objeto->estado !== 'disponible' &&
        $objeto->usuario_id !== $request->user()->id
    ) {
        abort(404);
    }

    $objeto->load('categoria');

    $objetosPerdidos = \App\Models\ObjetoPerdido::query()
        ->where('usuario_id', $request->user()->id)
        ->where('estado', 'perdido')
        ->orderByDesc('created_at')
        ->get();

    return view(
        'objetos-encontrados.show',
        compact('objeto', 'objetosPerdidos')
    );
}


public function explorar(Request $request)
{
    $busqueda = trim((string) $request->query('buscar', ''));

    $objetos = ObjetoEncontrado::query()
        ->with('categoria')
        ->where('estado', 'disponible')
        ->when($busqueda !== '', function ($consulta) use ($busqueda) {
            $consulta->where(function ($q) use ($busqueda) {
                $q->where('titulo', 'like', '%' . $busqueda . '%')
                  ->orWhere('descripcion', 'like', '%' . $busqueda . '%')
                  ->orWhere('ubicacion', 'like', '%' . $busqueda . '%');
            });
        })
        ->orderByDesc('created_at')
        ->paginate(12)
        ->withQueryString();

    $objetosPerdidos = \App\Models\ObjetoPerdido::query()
        ->where('usuario_id', $request->user()->id)
        ->where('estado', 'perdido')
        ->orderByDesc('created_at')
        ->get();

    return view(
        'objetos-encontrados.explorar',
        compact('objetos', 'busqueda', 'objetosPerdidos')
    );
}


    public function index(Request $request)
    {
        $objetos = ObjetoEncontrado::query()
            ->where('usuario_id', $request->user()->id)
            ->with('categoria')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('objetos-encontrados.index', compact('objetos'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();

        return view('objetos-encontrados.create', compact('categorias'));
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
            'ubicacion' => ['required', 'string', 'max:255'],
            'fecha_encontrado' => [
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
                ->store('objetos-encontrados', 'public');
        }

        try {
            $objeto = new ObjetoEncontrado();

            $objeto->usuario_id = $request->user()->id;
            $objeto->categoria_id = $datos['categoria_id'];
            $objeto->titulo = $datos['titulo'];
            $objeto->descripcion = $datos['descripcion'];
            $objeto->ubicacion = $datos['ubicacion'];
            $objeto->fecha_encontrado = $datos['fecha_encontrado'];
            $objeto->imagen = $rutaImagen;
            $objeto->estado = 'disponible';

            $objeto->save();
            
app(\App\Services\DetectorCoincidencias::class)
    ->detectarParaEncontrado($objeto);

        } catch (\Throwable $e) {
            if ($rutaImagen !== null) {
                Storage::disk('public')->delete($rutaImagen);
            }

            throw $e;
        }

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                'El objeto encontrado fue registrado correctamente.'
            );
    }
}
