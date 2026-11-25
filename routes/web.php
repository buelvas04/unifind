
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ObjetoPerdidoController;
use App\Http\Controllers\ObjetoEncontradoController;
use App\Http\Controllers\SolicitudRecuperacionController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConfirmacionEntregaController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    // Panel principal
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Objetos perdidos
    Route::get('/mis-objetos-perdidos', [
        ObjetoPerdidoController::class,
        'index'
    ])->name('objetos-perdidos.index');

    Route::get('/objetos-perdidos/crear', [
        ObjetoPerdidoController::class,
        'create'
    ])->name('objetos-perdidos.create');

    Route::post('/objetos-perdidos', [
        ObjetoPerdidoController::class,
        'store'
    ])->name('objetos-perdidos.store');

    Route::patch('/mis-objetos-perdidos/{objeto}/recuperado', [
        ObjetoPerdidoController::class,
        'marcarRecuperado'
    ])->name('objetos-perdidos.recuperado');

    // Objetos encontrados
    Route::get('/objetos-encontrados/crear', [
        ObjetoEncontradoController::class,
        'create'
    ])->name('objetos-encontrados.create');

    Route::get('/mis-objetos-encontrados', [
        ObjetoEncontradoController::class,
        'index'
    ])->name('objetos-encontrados.index');

    Route::get('/explorar-objetos-encontrados', [
        ObjetoEncontradoController::class,
        'explorar'
    ])->name('objetos-encontrados.explorar');

    Route::post('/objetos-encontrados', [
        ObjetoEncontradoController::class,
        'store'
    ])->name('objetos-encontrados.store');

Route::get('/objetos-encontrados/{objeto}', [
    ObjetoEncontradoController::class,
    'show'
])->name('objetos-encontrados.show');

    // Solicitudes de recuperación
    Route::post('/objetos-encontrados/{objeto}/solicitar', [
        SolicitudRecuperacionController::class,
        'store'
    ])->name('solicitudes.store');

    Route::get('/mis-solicitudes', [
        SolicitudRecuperacionController::class,
        'index'
    ])->name('solicitudes.index');

    Route::get('/solicitudes/recibidas', [
        SolicitudRecuperacionController::class,
        'recibidas'
    ])->name('solicitudes.recibidas');

    
Route::post('/solicitudes/{solicitud}/aceptar', [
    SolicitudRecuperacionController::class,
    'aceptar'
])->name('solicitudes.aceptar');

Route::post('/solicitudes/{solicitud}/rechazar', [
    SolicitudRecuperacionController::class,
    'rechazar'
])->name('solicitudes.rechazar');


    // Notificaciones
    Route::get('/notificaciones', [
        NotificacionController::class,
        'index'
    ])->name('notificaciones.index');

    Route::get('/notificaciones/recientes', [
        NotificacionController::class,
        'recientes'
    ])->name('notificaciones.recientes');

    
Route::post('/notificaciones/marcar-leidas', [
    NotificacionController::class,
    'marcarLeidas'
])->name('notificaciones.marcar-leidas');


    // Chat privado para solicitudes aceptadas

    Route::get('/chat/{solicitud}', [
        ChatController::class,
        'mostrar'
    ])->name('chat.mostrar');

    Route::post('/chat/{solicitud}/enviar', [
        ChatController::class,
        'enviar'
    ])->name('chat.enviar');

    
Route::get('/chat/{solicitud}/mensajes', [
    ChatController::class,
    'mensajes'
])->name('chat.mensajes');


    // Confirmación de entrega del objeto
    Route::post('/solicitudes/{solicitud}/confirmar-entrega', [
        ConfirmacionEntregaController::class,
        'confirmarEntrega'
    ])->name('solicitudes.confirmar-entrega');

    // Confirmación de recepción del objeto
    Route::post('/solicitudes/{solicitud}/confirmar-recepcion', [
        ConfirmacionEntregaController::class,
        'confirmarRecepcion'
    ])->name('solicitudes.confirmar-recepcion');

    
Route::get('/solicitudes/estados-confirmacion', function () {
    $usuario = auth()->user();

    $solicitudes = \App\Models\SolicitudRecuperacion::query()
        ->where(function ($consulta) use ($usuario) {
            $consulta->where('usuario_id', $usuario->id)
                ->orWhereHas('objetoEncontrado', function ($objetos) use ($usuario) {
                    $objetos->where('usuario_id', $usuario->id);
                });
        })
        ->get([
            'id',
            'estado',
            'confirmacion_entrega',
            'confirmacion_recepcion'
        ]);

    return response()->json([
        'solicitudes' => $solicitudes->map(function ($solicitud) {
            return [
                'id' => $solicitud->id,
                'estado' => $solicitud->estado,
                'entrega_confirmada' => (bool) $solicitud->confirmacion_entrega,
                'recepcion_confirmada' => (bool) $solicitud->confirmacion_recepcion,
                'recuperado' => (bool) (
                    $solicitud->confirmacion_entrega &&
                    $solicitud->confirmacion_recepcion
                )
            ];
        })
    ]);
})->name('solicitudes.estados-confirmacion');

});

// PERFIL Y CONFIGURACIÓN DE UNIFIND

Route::middleware('auth')->group(function () {

    Route::get('/perfil', [
        \App\Http\Controllers\PerfilController::class,
        'index'
    ])->name('perfil.index');

    Route::put('/perfil/datos', [
        \App\Http\Controllers\PerfilController::class,
        'actualizarDatos'
    ])->name('perfil.datos');

    Route::post('/perfil/foto', [
        \App\Http\Controllers\PerfilController::class,
        'actualizarFoto'
    ])->name('perfil.foto');

    Route::delete('/perfil/foto', [
        \App\Http\Controllers\PerfilController::class,
        'eliminarFoto'
    ])->name('perfil.foto.eliminar');

    Route::put('/perfil/correo', [
        \App\Http\Controllers\PerfilController::class,
        'actualizarCorreo'
    ])->name('perfil.correo');

    Route::put('/perfil/contrasena', [
        \App\Http\Controllers\PerfilController::class,
        'actualizarContrasena'
    ])->name('perfil.contrasena');

    Route::delete('/perfil', [
        \App\Http\Controllers\PerfilController::class,
        'eliminarCuenta'
    ])->name('perfil.eliminar');

});
