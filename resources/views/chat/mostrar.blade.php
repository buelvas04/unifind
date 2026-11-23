
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat privado | UniFind</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .encabezado {
            background: #1d4ed8;
            color: white;
            padding: 20px;
        }

        .encabezado-contenido {
            max-width: 850px;
            margin: auto;
        }

        .encabezado a {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        .encabezado h1 {
            margin: 12px 0 0;
        }

        .contenedor {
            max-width: 850px;
            margin: 25px auto;
            padding: 0 15px;
        }

        .chat {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        }

        .informacion {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .informacion h2 {
            margin: 0 0 8px;
            font-size: 20px;
        }

        .informacion p {
            margin: 0;
            color: #64748b;
        }

        .mensajes {
            height: 430px;
            overflow-y: auto;
            padding: 22px;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .mensaje {
            max-width: 75%;
            padding: 12px 16px;
            border-radius: 15px;
            background: #e2e8f0;
            align-self: flex-start;
            overflow-wrap: anywhere;
        }

        .mensaje.propio {
            background: #2563eb;
            color: white;
            align-self: flex-end;
        }

        .mensaje strong {
            display: block;
            font-size: 12px;
            margin-bottom: 6px;
        }

        .mensaje p {
            margin: 0;
            white-space: pre-wrap;
        }

        .mensaje small {
            display: block;
            margin-top: 8px;
            font-size: 11px;
            opacity: 0.75;
            text-align: right;
        }

        .vacio {
            text-align: center;
            color: #64748b;
            margin: auto;
        }

        .formulario {
            display: flex;
            gap: 10px;
            padding: 18px;
            border-top: 1px solid #e2e8f0;
        }

        .formulario textarea {
            flex: 1;
            min-width: 0;
            resize: vertical;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
        }

        .formulario button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 0 22px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
        }

        .formulario button:hover {
            background: #1d4ed8;
        }

        .errores {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        @media (max-width: 600px) {
            .mensaje {
                max-width: 90%;
            }

            .formulario {
                flex-direction: column;
            }

            .formulario button {
                padding: 13px;
            }
        }
    </style>
</head>

<body>

    <header class="encabezado">
        <div class="encabezado-contenido">
            <a href="{{ route('solicitudes.index') }}">
    ← Volver a mis solicitudes
</a>

            <h1>Chat privado de UniFind</h1>
        </div>
    </header>

    <main class="contenedor">

        @if ($errors->any())
            <div class="errores">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section class="chat">

            <div class="informacion">
                <h2>
                    {{ $solicitud->objetoEncontrado->titulo }}
                </h2>

                <p>
                    Solicitud #{{ $solicitud->id }}
                    · Recuperación aceptada
                </p>
            </div>

            <div class="mensajes" id="mensajes">

                @forelse ($mensajes as $mensaje)

                    <div class="mensaje {{ $mensaje->usuario_id == auth()->id() ? 'propio' : '' }}">

                        <strong>
                            {{ $mensaje->usuario_id == auth()->id() ? 'Tú' : ($mensaje->usuario?->name ?? 'Usuario') }}
                        </strong>

                        <p>{{ $mensaje->contenido }}</p>

                        <small>
                            {{ $mensaje->created_at->format('d/m/Y H:i') }}
                        </small>

                    </div>

                @empty

                    <div class="vacio">
                        Todavía no hay mensajes.
                        Escribe el primero para coordinar
                        la entrega del objeto.
                    </div>

                @endforelse

            </div>

            <form
                class="formulario"
                method="POST"
                action="{{ route('chat.enviar', $solicitud->id) }}"
            >
                @csrf

                <textarea
                    name="contenido"
                    rows="2"
                    maxlength="2000"
                    required
                    placeholder="Escribe un mensaje..."
                >{{ old('contenido') }}</textarea>

                <button type="submit">
                    Enviar
                </button>
            </form>

        </section>

    </main>

    
<script>
    const contenedorMensajes = document.getElementById('mensajes');

    const urlMensajes = @json(
        route('chat.mensajes', $solicitud->id)
    );

    const usuarioActual = @json(auth()->id());

    let ultimoContenido = '';

    function crearMensaje(mensaje) {
        const elemento = document.createElement('div');

        elemento.classList.add('mensaje');

        if (Number(mensaje.usuario_id) === Number(usuarioActual)) {
            elemento.classList.add('propio');
        }

        const nombre = document.createElement('strong');

        nombre.textContent =
            Number(mensaje.usuario_id) === Number(usuarioActual)
                ? 'Tú'
                : mensaje.usuario_nombre;

        const contenido = document.createElement('p');
        contenido.textContent = mensaje.contenido;

        const fecha = document.createElement('small');
        fecha.textContent = mensaje.fecha;

        elemento.appendChild(nombre);
        elemento.appendChild(contenido);
        elemento.appendChild(fecha);

        return elemento;
    }

    async function actualizarMensajes() {
        try {
            const respuesta = await fetch(urlMensajes, {
                headers: {
                    'Accept': 'application/json'
                },
                cache: 'no-store'
            });

            if (!respuesta.ok) {
                return;
            }

            const datos = await respuesta.json();

            const firma = datos.mensajes
                .map(mensaje => mensaje.id)
                .join(',');

            if (firma === ultimoContenido) {
                return;
            }

            const estabaAbajo =
                contenedorMensajes.scrollHeight -
                contenedorMensajes.scrollTop -
                contenedorMensajes.clientHeight < 100;

            contenedorMensajes.replaceChildren();

            if (datos.mensajes.length === 0) {
                const vacio = document.createElement('div');
                vacio.className = 'vacio';
                vacio.textContent =
                    'Todavía no hay mensajes. Escribe el primero para coordinar la entrega del objeto.';

                contenedorMensajes.appendChild(vacio);
            } else {
                datos.mensajes.forEach(mensaje => {
                    contenedorMensajes.appendChild(
                        crearMensaje(mensaje)
                    );
                });
            }

            ultimoContenido = firma;

            if (estabaAbajo) {
                contenedorMensajes.scrollTop =
                    contenedorMensajes.scrollHeight;
            }

        } catch (error) {
            console.error(
                'No se pudieron actualizar los mensajes:',
                error
            );
        }
    }

    if (contenedorMensajes) {
        contenedorMensajes.scrollTop =
            contenedorMensajes.scrollHeight;

        actualizarMensajes();

        setInterval(actualizarMensajes, 3000);
    }
</script>


</body>
</html>
