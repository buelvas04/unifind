<?php

namespace App\Http\Controllers;

use App\Models\SolicitudRecuperacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfirmacionEntregaController extends Controller
{
    public function confirmarEntrega(
        Request $request,
        SolicitudRecuperacion $solicitud
    ) {
        return DB::transaction(function () use ($request, $solicitud) {

            $solicitud = SolicitudRecuperacion::query()
                ->lockForUpdate()
                ->findOrFail($solicitud->id);

            $objeto = $solicitud->objetoEncontrado()
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                (int) $objeto->usuario_id === (int) $request->user()->id,
                403,
                'Solo quien encontró el objeto puede confirmar su entrega.'
            );

            abort_unless(
                $solicitud->estado === 'aceptada',
                403,
                'La solicitud debe estar aceptada.'
            );

            if (!$solicitud->confirmacion_entrega) {
                $solicitud->confirmacion_entrega = true;
                $solicitud->fecha_confirmacion_entrega = now();
                $solicitud->save();
            }

            $this->finalizarSiAmbosConfirmaron($solicitud, $objeto);

            return redirect()
                ->route('solicitudes.recibidas')
                ->with('success', 'Confirmación de entrega registrada correctamente.');
        });
    }

    public function confirmarRecepcion(
        Request $request,
        SolicitudRecuperacion $solicitud
    ) {
        return DB::transaction(function () use ($request, $solicitud) {

            $solicitud = SolicitudRecuperacion::query()
                ->lockForUpdate()
                ->findOrFail($solicitud->id);

            $objeto = $solicitud->objetoEncontrado()
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                (int) $solicitud->usuario_id === (int) $request->user()->id,
                403,
                'Solo quien solicitó el objeto puede confirmar su recepción.'
            );

            abort_unless(
                $solicitud->estado === 'aceptada',
                403,
                'La solicitud debe estar aceptada.'
            );

            if (!$solicitud->confirmacion_recepcion) {
                $solicitud->confirmacion_recepcion = true;
                $solicitud->fecha_confirmacion_recepcion = now();
                $solicitud->save();
            }

            $this->finalizarSiAmbosConfirmaron($solicitud, $objeto);

            return redirect()
                ->route('solicitudes.index')
                ->with('success', 'Confirmación de recepción registrada correctamente.');
        });
    }

    
private function finalizarSiAmbosConfirmaron(
    SolicitudRecuperacion $solicitud,
    $objeto
): void
{
    if (
        $solicitud->confirmacion_entrega &&
        $solicitud->confirmacion_recepcion
    ) {
        $objeto->estado = 'recuperado';
        $objeto->save();

        if ($solicitud->objeto_perdido_id !== null) {
            $objetoPerdido = \App\Models\ObjetoPerdido::query()
                ->whereKey($solicitud->objeto_perdido_id)
                ->where('usuario_id', $solicitud->usuario_id)
                ->lockForUpdate()
                ->first();

            if ($objetoPerdido && $objetoPerdido->estado === 'perdido') {
                $objetoPerdido->estado = 'recuperado';
                $objetoPerdido->save();
            }
        }
    }
}
 
}
