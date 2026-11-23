
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones | UniFind</title>

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
            max-width: 900px;
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

        .summary {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .count {
            display: inline-block;
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 20px;
            padding: 7px 13px;
            font-weight: bold;
        }

        .notification {
            background: white;
            border-radius: 12px;
            padding: 22px;
            margin-bottom: 15px;
            border-left: 5px solid #cbd5e1;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        }

        .notification.unread {
            border-left-color: #2563eb;
            background: #eff6ff;
        }

        .notification p {
            line-height: 1.6;
            margin-top: 0;
            overflow-wrap: anywhere;
        }

        .meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            font-size: 13px;
            color: #64748b;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            background: #e2e8f0;
            color: #475569;
            font-weight: bold;
        }

        .badge.unread {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .empty {
            background: white;
            padding: 40px 20px;
            border-radius: 12px;
            text-align: center;
        }

.ver-publicacion {
    display: inline-block;
    background: #2563eb;
    color: white;
    padding: 11px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 15px;
}

.ver-publicacion:hover {
    background: #1d4ed8;
}

        .pagination {
            margin-top: 25px;
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

        <h2>🔔 Mis notificaciones</h2>

        <div class="summary">
            <p>
                Aquí encontrarás avisos sobre solicitudes
                de recuperación y posibles coincidencias
                entre objetos perdidos y encontrados.
            </p>

            <span class="count">
                {{ $sinLeer }} sin leer
            </span>
        </div>

        @forelse ($notificaciones as $notificacion)

            <div class="notification {{ $notificacion->estado === 'no_leido' ? 'unread' : '' }}">

                
<p>
    {{ $notificacion->mensaje }}
</p>

@if ($notificacion->objeto_encontrado_id)
    <a
        href="{{ route('objetos-encontrados.show', $notificacion->objeto_encontrado_id) }}"
        class="ver-publicacion"
    >
        Ver publicación →
    </a>
@endif

<div class="meta">


                    <span>
                        {{ $notificacion->created_at?->format('d/m/Y H:i') }}
                    </span>

                    @if ($notificacion->estado === 'no_leido')
                        <span class="badge unread">
                            Nueva
                        </span>
                    @else
                        <span class="badge">
                            Leída
                        </span>
                    @endif

                </div>

            </div>

        @empty

            <div class="empty">
                <h3>🔔 No tienes notificaciones</h3>

                <p>
                    Cuando recibas una solicitud de recuperación
                    o se detecte una posible coincidencia,
                    tus avisos aparecerán aquí.
                </p>
            </div>

        @endforelse

        <div class="pagination">
            {{ $notificaciones->links() }}
        </div>

    </main>

</body>
</html>
