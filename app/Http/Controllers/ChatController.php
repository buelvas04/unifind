<?php

namespace App\Http\Controllers;

use App\Models\Mensaje;
use App\Models\SolicitudRecuperacion;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    private function verificarAcceso(
        Request $request,
        SolicitudRecuperacion $solicitud
    ): void {
        $solicitud->loadMissing('objetoEncontrado');

        $objeto = $solicitud->objetoEncontrado;

        abort_unless($objeto !== null, 404);

        $usuarioId = (int) $request->user()->id;

        $esSolicitante =
            (int) $solicitud->usuario_id === $usuarioId;

        $esQuienEncontro =
            (int) $objeto->usuario_id === $usuarioId;

        abort_unless(
            $esSolicitante || $esQuienEncontro,
            403,
            'No tienes permiso para acceder a esta conversación.'
        );

        abort_unless(
            $solicitud->estado === 'aceptada',
            403,
            'Esta conversación no está disponible.'
        );
    }

    public function mostrar(
        Request $request,
        SolicitudRecuperacion $solicitud
    ) {
        $this->verificarAcceso($request, $solicitud);

        $solicitud->loadMissing('objetoEncontrado');

        $mensajes = Mensaje::query()
            ->where('solicitud_recuperacion_id', $solicitud->id)
            ->with('usuario:id,name')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        return view('chat.mostrar', compact(
            'solicitud',
            'mensajes'
        ));
    }

    public function enviar(
        Request $request,
        SolicitudRecuperacion $solicitud
    ) {
        $this->verificarAcceso($request, $solicitud);

        $datos = $request->validate([
            'contenido' => [
                'required',
                'string',
                'max:2000'
            ]
        ]);

        Mensaje::create([
            'solicitud_recuperacion_id' => $solicitud->id,
            'usuario_id' => $request->user()->id,
            'contenido' => $datos['contenido']
        ]);

        return redirect()
            ->route('chat.mostrar', $solicitud->id)
            ->with(
                'success',
                'Mensaje enviado correctamente.'
            );
    }

    public function mensajes(
        Request $request,
        SolicitudRecuperacion $solicitud
    ) {
        $this->verificarAcceso($request, $solicitud);

        $mensajes = Mensaje::query()
            ->where(
                'solicitud_recuperacion_id',
                $solicitud->id
            )
            ->with('usuario:id,name')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get()
            ->map(function ($mensaje) {
                return [
                    'id' => $mensaje->id,
                    'usuario_id' => $mensaje->usuario_id,
                    'usuario_nombre' =>
                        $mensaje->usuario?->name ?? 'Usuario',
                    'contenido' => $mensaje->contenido,
                    'fecha' =>
                        $mensaje->created_at->format('d/m/Y H:i'),
                ];
            });

        return response()->json([
            'mensajes' => $mensajes,
        ]);
    }
}
