<?php

namespace App\Http\Controllers;

use App\Models\Mensaje;
use App\Models\ObjetoEncontrado;
use App\Models\ObjetoPerdido;
use App\Models\SolicitudRecuperacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PerfilController extends Controller
{
    public function index(Request $request): View
    {
        $usuario = $request->user()->load('rol');

        return view('perfil.index', compact('usuario'));
    }

    public function actualizarDatos(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'facultad' => ['nullable', 'string', 'max:120'],
            'programa' => ['nullable', 'string', 'max:120'],
        ]);

        $request->user()->forceFill([
            'name' => trim($datos['name']),
            'telefono' => $datos['telefono'] ?? null,
            'facultad' => $datos['facultad'] ?? null,
            'programa' => $datos['programa'] ?? null,
        ])->save();

        return back()
            ->with('success', 'Información personal actualizada correctamente.')
            ->with('perfil_tab', 'datos');
    }

    public function actualizarFoto(Request $request): RedirectResponse
    {
        $request->validate([
            'foto' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $usuario = $request->user();
        $anterior = $usuario->foto_perfil;

        $nueva = $request->file('foto')
            ->store('fotos-perfil', 'public');

        try {
            $usuario->forceFill([
                'foto_perfil' => $nueva,
            ])->save();
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($nueva);
            throw $e;
        }

        if ($anterior && str_starts_with($anterior, 'fotos-perfil/')) {
            Storage::disk('public')->delete($anterior);
        }

        return back()
            ->with('success', 'Foto de perfil actualizada.')
            ->with('perfil_tab', 'datos');
    }

    public function eliminarFoto(Request $request): RedirectResponse
    {
        $usuario = $request->user();
        $anterior = $usuario->foto_perfil;

        $usuario->forceFill([
            'foto_perfil' => null,
        ])->save();

        if ($anterior && str_starts_with($anterior, 'fotos-perfil/')) {
            Storage::disk('public')->delete($anterior);
        }

        return back()
            ->with('success', 'Foto de perfil eliminada.')
            ->with('perfil_tab', 'datos');
    }

    public function actualizarCorreo(Request $request): RedirectResponse
    {
        $usuario = $request->user();

        $request->merge([
            'email' => strtolower(trim((string) $request->input('email'))),
        ]);

        $datos = $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($usuario->id),
            ],
            'current_password' => [
                'required',
                'current_password:web',
            ],
        ]);

        $nuevoCorreo = strtolower(trim($datos['email']));

        if ($nuevoCorreo !== $usuario->email) {
            $usuario->forceFill([
                'email' => $nuevoCorreo,
                'email_verified_at' => null,
            ])->save();
        }

        return back()
            ->with(
                'success',
                'Correo actualizado. Utilízalo para tu próximo inicio de sesión.'
            )
            ->with('perfil_tab', 'correo');
    }

    public function actualizarContrasena(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'current_password' => [
                'required',
                'current_password:web',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'different:current_password',
                'confirmed',
            ],
        ]);

        $request->user()->forceFill([
            'password' => Hash::make($datos['password']),
        ])->save();

        return back()
            ->with('success', 'Contraseña actualizada correctamente.')
            ->with('perfil_tab', 'contrasena');
    }

    public function eliminarCuenta(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => [
                'required',
                'current_password:web',
            ],
            'confirmacion' => ['accepted'],
        ]);

        $usuario = $request->user();

        // Protegemos las relaciones e historial existente.
        $tieneHistorial =
            ObjetoPerdido::where('usuario_id', $usuario->id)->exists()
            || ObjetoEncontrado::where('usuario_id', $usuario->id)->exists()
            || SolicitudRecuperacion::where('usuario_id', $usuario->id)->exists()
            || Mensaje::where('usuario_id', $usuario->id)->exists();

        if ($tieneHistorial) {
            return back()
                ->withErrors([
                    'cuenta' =>
                        'Esta cuenta tiene reportes, solicitudes o mensajes ' .
                        'asociados. Para proteger el historial de recuperación, ' .
                        'no es posible eliminarla automáticamente.',
                ])
                ->with('perfil_tab', 'eliminar');
        }

        $foto = $usuario->foto_perfil;

        $usuario->delete();

        if ($foto && str_starts_with($foto, 'fotos-perfil/')) {
            Storage::disk('public')->delete($foto);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('status', 'Cuenta eliminada correctamente.');
    }
}
