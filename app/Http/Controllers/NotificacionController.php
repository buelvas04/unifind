<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();

        $notificaciones = Notificacion::query()
            ->where('usuario_id', $usuario->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        $sinLeer = Notificacion::query()
            ->where('usuario_id', $usuario->id)
            ->where('estado', 'no_leido')
            ->count();

        return view('notificaciones.index', compact(
            'notificaciones',
            'sinLeer'
        ));
    }

    public function recientes(Request $request)
    {
        $usuario = $request->user();

        $notificaciones = Notificacion::query()
            ->where('usuario_id', $usuario->id)
            ->orderByDesc('created_at')
            ->limit(5)
            
->get([
    'id',
    'objeto_encontrado_id',
    'mensaje',
    'estado',
    'created_at'
]);


        $sinLeer = Notificacion::query()
            ->where('usuario_id', $usuario->id)
            ->where('estado', 'no_leido')
            ->count();

        return response()->json([
            'sin_leer' => $sinLeer,
            'notificaciones' => $notificaciones
        ]);
    }

    public function marcarLeidas(Request $request)
    {
        Notificacion::where('usuario_id', $request->user()->id)
            ->where('estado', 'no_leido')
            ->update([
                'estado' => 'leido'
            ]);

        return response()->json([
            'success' => true,
            'mensaje' => 'Notificaciones marcadas como leídas.'
        ]);
    }
}
