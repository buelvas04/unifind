<?php

namespace App\Http\Controllers;

use App\Models\ObjetoEncontrado;
use App\Models\SolicitudRecuperacion;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SolicitudRecuperacionController extends Controller
{
    public function index(Request $request)
    {
        $solicitudes = SolicitudRecuperacion::query()
            ->where('usuario_id', $request->user()->id)
            ->with('objetoEncontrado.categoria')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('solicitudes.index', compact('solicitudes'));
    }

    public function recibidas(Request $request)
    {
        $solicitudes = SolicitudRecuperacion::query()
            ->whereHas('objetoEncontrado', function ($consulta) use ($request) {
                $consulta->where('usuario_id', $request->user()->id);
            })
            ->with('objetoEncontrado.categoria')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('solicitudes.recibidas', compact('solicitudes'));
    }

    public function store(Request $request, ObjetoEncontrado $objeto)
    {
        $usuario = $request->user();

        $datos = $request->validate([
            'objeto_perdido_id' => [
                'nullable',
                'integer',
                Rule::exists('objetos_perdidos', 'id')
                    ->where(function ($consulta) use ($usuario) {
                        $consulta->where('usuario_id', $usuario->id)
                            ->where('estado', 'perdido');
                    }),
            ],
        ]);

        return DB::transaction(function () use ($usuario, $objeto, $datos) {

            $objeto = ObjetoEncontrado::query()
                ->whereKey($objeto->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ((int) $objeto->usuario_id === (int) $usuario->id) {
                return back()->with(
                    'error',
                    'No puedes solicitar un objeto que tú mismo publicaste.'
                );
            }

            if ($objeto->estado !== 'disponible') {
                return back()->with(
                    'error',
                    'Este objeto ya no está disponible.'
                );
            }

            $solicitudActiva = SolicitudRecuperacion::query()
                ->where('usuario_id', $usuario->id)
                ->where('objeto_encontrado_id', $objeto->id)
                ->whereIn('estado', ['pendiente', 'aceptada'])
                ->exists();

            if ($solicitudActiva) {
                return back()->with(
                    'error',
                    'Ya tienes una solicitud pendiente o aceptada para este objeto.'
                );
            }

            $solicitud = new SolicitudRecuperacion();

            $solicitud->usuario_id = $usuario->id;
            $solicitud->objeto_encontrado_id = $objeto->id;
            $solicitud->objeto_perdido_id =
                $datos['objeto_perdido_id'] ?? null;
            $solicitud->estado = 'pendiente';

            $solicitud->save();

            $notificacion = new Notificacion();

            $notificacion->usuario_id = $objeto->usuario_id;

            $notificacion->mensaje =
                'Has recibido una nueva solicitud de recuperación para el objeto: '
                . $objeto->titulo;

            $notificacion->estado = 'no_leido';

            $notificacion->save();

            return back()->with(
                'success',
                'Tu solicitud de recuperación fue enviada correctamente.'
            );
        });
    }

    public function aceptar(
        Request $request,
        SolicitudRecuperacion $solicitud
    ) {
        return DB::transaction(function () use ($request, $solicitud) {

            $solicitud = SolicitudRecuperacion::whereKey($solicitud->id)
                ->lockForUpdate()
                ->firstOrFail();

            $objeto = ObjetoEncontrado::whereKey(
                $solicitud->objeto_encontrado_id
            )->lockForUpdate()->firstOrFail();

            abort_unless(
                (int) $objeto->usuario_id === (int) $request->user()->id,
                403
            );

            if (
                $solicitud->estado !== 'pendiente' ||
                $objeto->estado !== 'disponible'
            ) {
                return back()->with(
                    'error',
                    'Esta solicitud ya no se puede aceptar.'
                );
            }

            $otraAceptada = SolicitudRecuperacion::where(
                'objeto_encontrado_id',
                $objeto->id
            )->where('estado', 'aceptada')->exists();

            if ($otraAceptada) {
                return back()->with(
                    'error',
                    'Ya existe una solicitud aceptada para este objeto.'
                );
            }

            $solicitud->estado = 'aceptada';
            $solicitud->save();

            SolicitudRecuperacion::where(
                'objeto_encontrado_id',
                $objeto->id
            )
                ->where('id', '!=', $solicitud->id)
                ->where('estado', 'pendiente')
                ->update(['estado' => 'rechazada']);

            Notificacion::create([
                'usuario_id' => $solicitud->usuario_id,
                'mensaje' => 'Tu solicitud para recuperar el objeto '
                    . $objeto->titulo
                    . ' fue aceptada. Próximamente podrás comunicarte para coordinar la entrega.',
                'estado' => 'no_leido',
            ]);

            return back()->with(
                'success',
                'Solicitud aceptada correctamente.'
            );
        });
    }

    public function rechazar(
        Request $request,
        SolicitudRecuperacion $solicitud
    ) {
        return DB::transaction(function () use ($request, $solicitud) {

            $solicitud = SolicitudRecuperacion::whereKey($solicitud->id)
                ->lockForUpdate()
                ->firstOrFail();

            $objeto = ObjetoEncontrado::whereKey(
                $solicitud->objeto_encontrado_id
            )->firstOrFail();

            abort_unless(
                (int) $objeto->usuario_id === (int) $request->user()->id,
                403
            );

            if ($solicitud->estado !== 'pendiente') {
                return back()->with(
                    'error',
                    'Esta solicitud ya fue atendida.'
                );
            }

            $solicitud->estado = 'rechazada';
            $solicitud->save();

            Notificacion::create([
                'usuario_id' => $solicitud->usuario_id,
                'mensaje' => 'Tu solicitud para recuperar el objeto '
                    . $objeto->titulo
                    . ' fue rechazada.',
                'estado' => 'no_leido',
            ]);

            return back()->with(
                'success',
                'Solicitud rechazada correctamente.'
            );
        });
    }
}
