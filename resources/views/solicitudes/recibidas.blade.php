
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes recibidas | UniFind</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f1f5f9;
            font-family: Arial, sans-serif;
            color: #1e293b;
        }

        header {
            background: #1d4ed8;
            color: white;
            padding: 20px 30px;
        }

        header h1 {
            margin: 0;
        }

        main {
            max-width: 1100px;
            margin: 35px auto;
            padding: 0 20px;
        }

        h2 {
            color: #1d4ed8;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #2563eb;
            text-decoration: none;
        }

        .card {
            background: white;
            padding: 24px;
            margin-bottom: 18px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .card h3 {
            margin-top: 0;
            color: #1d4ed8;
        }

        .card p {
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .estado {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 8px;
            background: #fef3c7;
            color: #92400e;
            font-size: 13px;
            font-weight: bold;
        }

        .aceptada,
        .recuperada {
            background: #dcfce7;
            color: #166534;
        }

        .rechazada {
            background: #fee2e2;
            color: #991b1b;
        }

        .empty {
            background: white;
            padding: 35px;
            text-align: center;
            border-radius: 12px;
        }

        .pagination {
            margin-top: 25px;
        }

        .acciones {
            display: flex;
            gap: 12px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .acciones form {
            margin: 0;
        }

        .btn-aceptar,
        .btn-rechazar,
        .btn-confirmar {
            border: none;
            padding: 12px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-aceptar,
        .btn-confirmar {
            background: #16a34a;
            color: white;
        }

        .btn-aceptar:hover,
        .btn-confirmar:hover {
            background: #15803d;
        }

        .btn-rechazar {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-rechazar:hover {
            background: #fecaca;
        }

        .btn-chat {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
            padding: 12px 20px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            transition: background 0.2s;
        }

        .btn-chat:hover {
            background: #1d4ed8;
        }

        .mensaje {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .mensaje-exito {
            background: #dcfce7;
            color: #166534;
        }

        .mensaje-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .texto-aceptada {
            color: #166534;
            font-weight: bold;
        }

        .texto-rechazada {
            color: #991b1b;
            font-weight: bold;
        }

        .recuperacion {
            margin-top: 20px;
            padding: 20px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 12px;
        }

        .recuperacion h4 {
            margin: 0 0 12px;
            color: #1d4ed8;
            font-size: 17px;
        }

        .recuperacion-descripcion {
            font-size: 14px;
            color: #475569;
        }

        .estados-confirmacion {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin: 16px 0;
        }

        .estado-confirmacion {
            flex: 1;
            min-width: 190px;
            background: white;
            border: 1px solid #dbeafe;
            border-radius: 10px;
            padding: 14px;
        }

        .estado-confirmacion strong {
            display: block;
            margin-bottom: 8px;
        }

        .pendiente-confirmacion {
            color: #92400e;
            font-size: 14px;
        }

        .confirmada {
            color: #166534;
            font-weight: bold;
            font-size: 14px;
        }

        .aviso-espera {
            margin-top: 14px;
            padding: 13px;
            background: #fef3c7;
            color: #92400e;
            border-radius: 8px;
        }

        .aviso-completado {
            margin-top: 14px;
            padding: 14px;
            background: #dcfce7;
            color: #166534;
            border-radius: 8px;
            font-weight: bold;
        }

        .nota-confirmacion {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 0;
        }

        @media (max-width: 600px) {
            header {
                padding: 18px 20px;
            }

            main {
                margin: 20px auto;
                padding: 0 15px;
            }

            .card {
                padding: 18px;
            }

            .acciones {
                flex-direction: column;
            }

            .acciones form,
            .acciones button {
                width: 100%;
            }

            .estados-confirmacion {
                flex-direction: column;
            }

            .btn-confirmar {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <header>
        <h1>UniFind</h1>
    </header>

    <main>

        <a href="{{ route('dashboard') }}" class="back">
            ← Volver al panel
        </a>

        <h2>Solicitudes recibidas</h2>

        @if (session('success'))
            <div class="mensaje mensaje-exito">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mensaje mensaje-error">
                {{ session('error') }}
            </div>
        @endif

        <p>
            Consulta las solicitudes que otras personas
            han enviado para recuperar los objetos que publicaste.
        </p>

        @forelse ($solicitudes as $solicitud)

            @php
                $entregaConfirmada = (bool) $solicitud->confirmacion_entrega;
                $recepcionConfirmada = (bool) $solicitud->confirmacion_recepcion;

                $recuperacionCompleta =
                    $entregaConfirmada && $recepcionConfirmada;
            @endphp

            <div class="card">

                <h3>
                    {{ $solicitud->objetoEncontrado?->titulo ?? 'Objeto no disponible' }}
                </h3>

                <p>
                    <strong>Categoría:</strong>
                    {{ $solicitud->objetoEncontrado?->categoria?->nombre ?? 'Sin categoría' }}
                </p>

                <p>
                    <strong>Lugar donde fue encontrado:</strong>
                    {{ $solicitud->objetoEncontrado?->ubicacion ?? 'No disponible' }}
                </p>

                <p>
                    <strong>Fecha de solicitud:</strong>
                    {{ $solicitud->created_at?->format('d/m/Y') }}
                </p>

                <p>
                    <strong>Estado:</strong>

                    @if ($recuperacionCompleta)
                        <span class="estado recuperada">
                            Recuperado
                        </span>
                    @else
                        <span class="estado {{ $solicitud->estado }}">
                            {{ ucfirst($solicitud->estado) }}
                        </span>
                    @endif
                </p>

                <p>
                    <strong>Número de solicitud:</strong>
                    #{{ $solicitud->id }}
                </p>

                @if ($solicitud->estado === 'pendiente')

                    <div class="acciones">

                        <form
                            method="POST"
                            action="{{ route('solicitudes.aceptar', $solicitud->id) }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="btn-aceptar"
                            >
                                ✓ Aceptar solicitud
                            </button>
                        </form>

                        <form
                            method="POST"
                            action="{{ route('solicitudes.rechazar', $solicitud->id) }}"
                            onsubmit="return confirm('¿Seguro que deseas rechazar esta solicitud?');"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="btn-rechazar"
                            >
                                ✕ Rechazar solicitud
                            </button>
                        </form>

                    </div>

                @elseif ($solicitud->estado === 'aceptada')

                    <p class="texto-aceptada">
                        ✓ Solicitud aceptada.
                        Puedes comunicarte con la persona
                        para coordinar la entrega del objeto.
                    </p>

                    <a
                        href="{{ route('chat.mostrar', $solicitud->id) }}"
                        class="btn-chat"
                    >
                        💬 Abrir chat
                    </a>

                    <div class="recuperacion">

                        <h4>Estado de recuperación</h4>

                        <p class="recuperacion-descripcion">
                            Para finalizar la recuperación,
                            ambas personas deben confirmar
                            que el objeto fue entregado y recibido.
                        </p>

                        <div class="estados-confirmacion">

                            <div class="estado-confirmacion">
                                <strong>Entrega del objeto</strong>

                                @if ($entregaConfirmada)
                                    <span class="confirmada">
                                        ✓ Confirmada
                                    </span>
                                @else
                                    <span class="pendiente-confirmacion">
                                        Pendiente
                                    </span>
                                @endif
                            </div>

                            <div class="estado-confirmacion">
                                <strong>Recepción del objeto</strong>

                                @if ($recepcionConfirmada)
                                    <span class="confirmada">
                                        ✓ Confirmada
                                    </span>
                                @else
                                    <span class="pendiente-confirmacion">
                                        Pendiente
                                    </span>
                                @endif
                            </div>

                        </div>

                        @if ($recuperacionCompleta)

                            <div class="aviso-completado">
                                ✓ Recuperación completada.
                                Ambas personas confirmaron
                                la entrega y recepción del objeto.
                            </div>

                        @elseif (!$entregaConfirmada)

                            <form
                                method="POST"
                                action="{{ route('solicitudes.confirmar-entrega', $solicitud->id) }}"
                                onsubmit="return confirm('¿Confirmas que ya entregaste físicamente este objeto a la persona correspondiente?');"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="btn-confirmar"
                                >
                                    ✓ Confirmar que entregué el objeto
                                </button>
                            </form>

                        @else

                            <div class="aviso-espera">
                                ✓ Ya confirmaste la entrega.
                                Estamos esperando que la otra persona
                                confirme la recepción.
                            </div>

                        @endif

                        <p class="nota-confirmacion">
                            Confirma únicamente cuando hayas entregado
                            físicamente el objeto.
                        </p>

                    </div>

                @elseif ($solicitud->estado === 'rechazada')

                    <p class="texto-rechazada">
                        Esta solicitud fue rechazada.
                    </p>

                @endif

            </div>

        @empty

            <div class="empty">
                <h3>Aún no has recibido solicitudes.</h3>

                <p>
                    Cuando alguien solicite recuperar uno de los
                    objetos que publicaste, su solicitud aparecerá aquí.
                </p>
            </div>

        @endforelse

        <div class="pagination">
            {{ $solicitudes->links() }}
        </div>

    </main>

<script>
    const urlEstados = @json(route('solicitudes.estados-confirmacion'));

    let estadosAnteriores = null;

    async function actualizarEstados() {
        try {
            const respuesta = await fetch(urlEstados, {
                headers: {
                    'Accept': 'application/json'
                },
                cache: 'no-store'
            });

            if (!respuesta.ok) {
                return;
            }

            const datos = await respuesta.json();

            const estadosActuales = JSON.stringify(
                datos.solicitudes.map(solicitud => ({
                    id: solicitud.id,
                    estado: solicitud.estado,
                    entrega: solicitud.entrega_confirmada,
                    recepcion: solicitud.recepcion_confirmada,
                    recuperado: solicitud.recuperado
                }))
            );

            if (
                estadosAnteriores !== null &&
                estadosActuales !== estadosAnteriores
            ) {
                window.location.reload();
                return;
            }

            estadosAnteriores = estadosActuales;

        } catch (error) {
            console.error(
                'No se pudieron actualizar los estados:',
                error
            );
        }
    }

    actualizarEstados();

    setInterval(actualizarEstados, 3000);
</script>

</body>
</html>
