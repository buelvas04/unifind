
@php
    $usuarioActual = auth()->user();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis solicitudes | UniFind</title>

    <style>
        :root {
            --azul: #1763d4;
            --azul-oscuro: #0e2b51;
            --texto: #132b4b;
            --secundario: #61748f;
            --fondo: #f1f5fb;
            --borde: #e0e9f5;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--fondo);
            color: var(--texto);
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        a { color: inherit; text-decoration: none; }
        button, input { font: inherit; }
        button { cursor: pointer; }

        .icon {
            width: 21px;
            height: 21px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        .app {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 245px minmax(0, 1fr);
        }

        /* MENÚ LATERAL */

        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            display: flex;
            flex-direction: column;
            padding: 25px 14px 18px;
            color: #e9f2ff;
            background: linear-gradient(
                155deg,
                #153b6b,
                #0d274b 65%,
                #0a2141
            );
            z-index: 30;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 4px 11px 27px;
            color: #fff;
            font-size: 27px;
            font-weight: 800;
        }

        .brand-icon {
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #2374e6;
        }

        .brand-icon .icon {
            width: 27px;
            height: 27px;
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 13px 14px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.4;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, .11);
        }

        .nav-link.active {
            color: #fff;
            background: linear-gradient(
                90deg,
                #287ee8,
                #1760cd
            );
        }

        .nav-divider {
            border-top: 1px solid rgba(211, 230, 255, .22);
            margin: 14px 10px;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 25px;
        }

        .logout-form { margin: 0; }

        .logout {
            display: flex;
            align-items: center;
            gap: 13px;
            width: 100%;
            padding: 12px 14px;
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 10px;
            color: #fff;
            background: rgba(255, 255, 255, .09);
            font-weight: 600;
            text-align: left;
        }

        .logout:hover {
            background: rgba(255, 255, 255, .18);
        }

        /* BARRA SUPERIOR */

        .workspace { min-width: 0; }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            min-height: 80px;
            padding: 14px 32px;
            background: #fff;
            border-bottom: 1px solid var(--borde);
        }

        .topbar-search {
            position: relative;
            flex: 1;
            max-width: 650px;
            min-width: 0;
        }

        .topbar-search .icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #6884a6;
        }

        .topbar-search input {
            width: 100%;
            height: 43px;
            padding: 0 15px 0 44px;
            border: 1px solid #ccd9ea;
            border-radius: 10px;
            background: #f8fbff;
            outline: none;
        }

        .topbar-search input:focus {
            border-color: #397ae1;
            box-shadow: 0 0 0 3px rgba(57, 122, 225, .13);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .bell {
            display: grid;
            place-items: center;
            width: 43px;
            height: 43px;
            border-radius: 12px;
            background: #edf4ff;
            color: #174b91;
        }

        .bell:hover { background: #dbeaff; }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 700;
        }

        .avatar {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #e2edff;
            color: #145ab4;
            font-weight: 800;
        }

        .user-name {
            max-width: 165px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .menu-toggle {
            display: none;
            padding: 9px;
            border: 0;
            border-radius: 9px;
            background: #ecf3ff;
            color: #1557b6;
        }

        .overlay { display: none; }

        /* ENCABEZADO DE LA PÁGINA */

        main {
            max-width: 1440px;
            margin: 0 auto;
            padding: 32px 34px 50px;
        }

        .back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 15px;
            padding: 9px 15px;
            border-radius: 999px;
            background: #dfecff;
            color: #1559bc;
            font-size: 13px;
            font-weight: 700;
        }

        .back:hover { background: #cfe2ff; }

        .back .icon {
            width: 17px;
            height: 17px;
        }

        h1 {
            margin: 0 0 8px;
            color: #0e2c51;
            font-size: clamp(23px, 2.2vw, 30px);
        }

        .intro {
            margin: 0 0 24px;
            color: var(--secundario);
            font-size: 15px;
            line-height: 1.5;
        }

        .mensaje {
            padding: 15px 18px;
            margin: 0 0 19px;
            border: 1px solid transparent;
            border-radius: 11px;
            font-size: 14px;
            font-weight: 600;
        }

        .mensaje-exito {
            background: #dcfce7;
            color: #166534;
            border-color: #9adeb6;
        }

        .mensaje-error {
            background: #fee2e2;
            color: #991b1b;
            border-color: #efb2b2;
        }

        /* TARJETAS DE SOLICITUDES */

        .card {
            margin-bottom: 20px;
            padding: 19px;
            border: 1px solid var(--borde);
            border-radius: 18px;
            background: white;
            box-shadow: 0 8px 25px rgba(22, 65, 118, .08);
        }

        .request-overview {
            display: grid;
            grid-template-columns:
                minmax(170px, 230px)
                minmax(0, 1fr)
                minmax(230px, 295px);
            gap: 22px;
            align-items: start;
        }

        .request-photo {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 190px;
            overflow: hidden;
            border-radius: 13px;
            background: #f1f5f9;
            color: #617d9e;
            font-size: 13px;
            font-weight: 700;
            text-align: center;
        }

        .request-photo img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
        }

        .request-details { min-width: 0; }

        .request-details h2 {
            margin: 2px 0 15px;
            color: #1261c8;
            font-size: 22px;
            overflow-wrap: anywhere;
        }

        .request-details p {
            margin: 9px 0;
            font-size: 14px;
            line-height: 1.55;
            overflow-wrap: anywhere;
        }

        .request-details strong { color: #19385e; }

        .request-actions {
            min-width: 0;
            align-self: stretch;
            border-left: 1px solid #e1eaf5;
            padding-left: 22px;
        }

        .request-actions .status-title {
            margin: 0 0 10px;
            font-size: 14px;
            font-weight: 800;
            color: #244362;
        }

        .estado {
            display: inline-block;
            padding: 8px 13px;
            border-radius: 999px;
            background: #fef3c7;
            color: #92400e;
            font-size: 13px;
            font-weight: 800;
        }

        .estado.aceptada,
        .estado.recuperada {
            background: #dcfce7;
            color: #166534;
        }

        .estado.rechazada {
            background: #fee2e2;
            color: #991b1b;
        }

        .texto-aceptada,
        .texto-rechazada {
            margin: 15px 0 6px;
            line-height: 1.55;
            font-size: 13px;
            font-weight: 600;
        }

        .texto-aceptada { color: #166534; }
        .texto-rechazada { color: #991b1b; }

        .btn-chat {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 12px;
            padding: 12px 19px;
            border-radius: 10px;
            background: #1763d4;
            color: white;
            font-size: 14px;
            font-weight: 700;
        }

        .btn-chat:hover { background: #1052b8; }

        .btn-chat .icon {
            width: 18px;
            height: 18px;
        }

        /* CONFIRMACIÓN DE RECUPERACIÓN */

        .recuperacion {
            margin-top: 18px;
            padding: 19px;
            border: 1px solid #bfdbfe;
            border-radius: 13px;
            background: #eff6ff;
        }

        .recuperacion h3 {
            margin: 0 0 9px;
            color: #175abe;
            font-size: 17px;
        }

        .recuperacion-descripcion {
            margin: 0;
            color: #475569;
            font-size: 14px;
            line-height: 1.55;
        }

        .estados-confirmacion {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 13px;
            margin: 16px 0;
        }

        .estado-confirmacion {
            padding: 15px;
            border: 1px solid #dbeafe;
            border-radius: 10px;
            background: #fff;
        }

        .estado-confirmacion strong {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .pendiente-confirmacion {
            color: #92400e;
            font-size: 14px;
        }

        .confirmada {
            color: #166534;
            font-size: 14px;
            font-weight: 700;
        }

        .btn-confirmar {
            padding: 12px 18px;
            border: 0;
            border-radius: 9px;
            background: #16a34a;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
        }

        .btn-confirmar:hover { background: #15803d; }

        .aviso-espera {
            margin-top: 14px;
            padding: 13px;
            border-radius: 9px;
            background: #fef3c7;
            color: #92400e;
        }

        .aviso-completado {
            margin-top: 14px;
            padding: 14px;
            border-radius: 9px;
            background: #dcfce7;
            color: #166534;
            font-weight: 700;
        }

        .nota-confirmacion {
            margin-bottom: 0;
            color: #64748b;
            font-size: 13px;
            line-height: 1.5;
        }

        .empty {
            padding: 40px 24px;
            border-radius: 16px;
            border: 1px solid var(--borde);
            background: #fff;
            text-align: center;
            box-shadow: 0 8px 25px rgba(22, 65, 118, .06);
        }

        .empty h2 {
            margin-top: 0;
            color: #153c6d;
        }

        .empty p { color: var(--secundario); }
        .pagination { margin-top: 25px; }

        /* TABLET */

        @media (max-width: 1180px) {
            .request-overview {
                grid-template-columns:
                    minmax(170px, 230px)
                    minmax(0, 1fr);
            }

            .request-actions {
                grid-column: 1 / -1;
                border-left: 0;
                border-top: 1px solid #e1eaf5;
                padding: 16px 0 0;
            }
        }

        /* CELULARES */

        @media (max-width: 760px) {
            .app { display: block; }

            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                width: 255px;
                transform: translateX(-100%);
                transition: transform .2s;
            }

            .sidebar.open { transform: translateX(0); }

            .overlay.open {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(6, 24, 48, .55);
                z-index: 25;
            }

            .menu-toggle {
                display: grid;
                place-items: center;
            }

            .topbar {
                padding: 12px 15px;
                gap: 10px;
            }

            .user-name { display: none; }
            .topbar-actions { gap: 9px; }

            main { padding: 25px 15px 35px; }
            .card { padding: 13px; }

            .request-overview {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .request-photo { height: 230px; }
            .request-actions { grid-column: auto; }

            .estados-confirmacion {
                grid-template-columns: 1fr;
            }

            .btn-confirmar { width: 100%; }
            .recuperacion { padding: 15px; }
        }
    </style>
</head>

<body>

    <!-- ICONOS -->

    <svg
        xmlns="http://www.w3.org/2000/svg"
        width="0"
        height="0"
        aria-hidden="true"
        style="position:absolute;overflow:hidden"
    >
        <symbol id="i-logo" viewBox="0 0 24 24">
            <path d="m2 9 10-5 10 5-10 5L2 9Z"/>
            <path d="M6 11v5c4 3 8 3 12 0v-5M22 9v6"/>
        </symbol>

        <symbol id="i-home" viewBox="0 0 24 24">
            <path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10Z"/>
            <path d="M9 21v-7h6v7"/>
        </symbol>

        <symbol id="i-search" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="7"/>
            <path d="m16 16 5 5"/>
        </symbol>

        <symbol id="i-plus" viewBox="0 0 24 24">
            <path d="M12 5v14M5 12h14"/>
        </symbol>

        <symbol id="i-box" viewBox="0 0 24 24">
            <path d="m3 7 9-4 9 4v10l-9 4-9-4V7Z"/>
            <path d="m3 7 9 4 9-4M12 11v10"/>
        </symbol>

        <symbol id="i-list" viewBox="0 0 24 24">
            <path d="M9 6h12M9 12h12M9 18h12M4 6h.01M4 12h.01M4 18h.01"/>
        </symbol>

        <symbol id="i-check" viewBox="0 0 24 24">
            <rect x="4" y="3" width="16" height="18" rx="2"/>
            <path d="m8 12 2.5 2.5L16 9"/>
        </symbol>

        <symbol id="i-bell" viewBox="0 0 24 24">
            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/>
        </symbol>

        <symbol id="i-user" viewBox="0 0 24 24">
            <circle cx="12" cy="8" r="4"/>
            <path d="M4 21c0-5 3-8 8-8s8 3 8 8"/>
        </symbol>

        <symbol id="i-out" viewBox="0 0 24 24">
            <path d="M10 4H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h5M15 8l4 4-4 4M19 12H9"/>
        </symbol>

        <symbol id="i-arrow-left" viewBox="0 0 24 24">
            <path d="M19 12H5m6 6-6-6 6-6"/>
        </symbol>

        <symbol id="i-menu" viewBox="0 0 24 24">
            <path d="M4 6h16M4 12h16M4 18h16"/>
        </symbol>

        <symbol id="i-chat" viewBox="0 0 24 24">
            <path d="M20 11a8 8 0 0 1-8 8 9 9 0 0 1-4-.9L3 20l1.8-5A8 8 0 1 1 20 11Z"/>
        </symbol>
    </svg>

    <div class="overlay" id="overlay"></div>

    <div class="app">

        <!-- MENÚ LATERAL -->

        <aside
            class="sidebar"
            id="sidebar"
            aria-label="Navegación principal"
        >
            <a href="{{ route('dashboard') }}" class="brand">
                <span class="brand-icon">
                    <svg class="icon"><use href="#i-logo"/></svg>
                </span>
                <span>UniFind</span>
            </a>

            <nav class="nav">

                <a class="nav-link" href="{{ route('dashboard') }}">
                    <svg class="icon"><use href="#i-home"/></svg>
                    Inicio
                </a>

                <a
                    class="nav-link"
                    href="{{ route('objetos-encontrados.explorar') }}"
                >
                    <svg class="icon"><use href="#i-search"/></svg>
                    Explorar objetos
                </a>

                <a
                    class="nav-link"
                    href="{{ route('objetos-perdidos.index') }}"
                >
                    <svg class="icon"><use href="#i-list"/></svg>
                    Mis objetos perdidos
                </a>

                <a
                    class="nav-link"
                    href="{{ route('objetos-encontrados.index') }}"
                >
                    <svg class="icon"><use href="#i-box"/></svg>
                    Mis objetos encontrados
                </a>

                <a
                    class="nav-link"
                    href="{{ route('objetos-perdidos.create') }}"
                >
                    <svg class="icon"><use href="#i-plus"/></svg>
                    Reportar pérdida
                </a>

                <a
                    class="nav-link"
                    href="{{ route('objetos-encontrados.create') }}"
                >
                    <svg class="icon"><use href="#i-plus"/></svg>
                    Reportar hallazgo
                </a>

                <a
                    class="nav-link active"
                    href="{{ route('solicitudes.index') }}"
                    aria-current="page"
                >
                    <svg class="icon"><use href="#i-check"/></svg>
                    Mis solicitudes
                </a>

                <a
                    class="nav-link"
                    href="{{ route('solicitudes.recibidas') }}"
                >
                    <svg class="icon"><use href="#i-list"/></svg>
                    Solicitudes recibidas
                </a>

                <a
                    class="nav-link"
                    href="{{ route('notificaciones.index') }}"
                >
                    <svg class="icon"><use href="#i-bell"/></svg>
                    Notificaciones
                </a>

                <div class="nav-divider"></div>

                @if (Route::has('perfil.index'))
                    <a
                        class="nav-link"
                        href="{{ route('perfil.index') }}"
                    >
                        <svg class="icon"><use href="#i-user"/></svg>
                        Mi perfil
                    </a>
                @endif

            </nav>

            <div class="sidebar-bottom">
                <form
                    class="logout-form"
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button class="logout" type="submit">
                        <svg class="icon"><use href="#i-out"/></svg>
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </aside>

        <!-- ÁREA PRINCIPAL -->

        <div class="workspace">

            <header class="topbar">

                <button
                    class="menu-toggle"
                    id="menuToggle"
                    type="button"
                    aria-label="Abrir menú"
                    aria-expanded="false"
                >
                    <svg class="icon"><use href="#i-menu"/></svg>
                </button>

                <form
                    class="topbar-search"
                    method="GET"
                    action="{{ route('objetos-encontrados.explorar') }}"
                >
                    <svg class="icon"><use href="#i-search"/></svg>

                    <input
                        type="search"
                        name="buscar"
                        placeholder="Buscar celular, computador, llaves..."
                        aria-label="Buscar objetos encontrados"
                    >
                </form>

                <div class="topbar-actions">

                    <a
                        href="{{ route('notificaciones.index') }}"
                        class="bell"
                        aria-label="Ver notificaciones"
                        title="Ver notificaciones"
                    >
                        <svg class="icon"><use href="#i-bell"/></svg>
                    </a>

                    <div class="user-chip">
                        <span class="avatar">
                            {{ strtoupper(mb_substr(trim($usuarioActual->name), 0, 1)) }}
                        </span>

                        <span class="user-name">
                            {{ $usuarioActual->name }}
                        </span>
                    </div>

                </div>
            </header>

            <main>

                <!-- VOLVER AL PANEL -->

                <a href="{{ route('dashboard') }}" class="back">
                    <svg class="icon">
                        <use href="#i-arrow-left"/>
                    </svg>
                    Volver al panel
                </a>

                <h1>Mis solicitudes de recuperación</h1>

                <p class="intro">
                    Consulta las solicitudes que has enviado
                    para recuperar objetos encontrados.
                </p>

                <!-- MENSAJES ORIGINALES -->

                @if (session('success'))
                    <div class="mensaje mensaje-exito" role="status">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mensaje mensaje-error" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- SOLICITUDES -->

                @forelse ($solicitudes as $solicitud)

                    @php
                        $entregaConfirmada =
                            (bool) $solicitud->confirmacion_entrega;

                        $recepcionConfirmada =
                            (bool) $solicitud->confirmacion_recepcion;

                        $recuperacionCompleta =
                            $entregaConfirmada && $recepcionConfirmada;
                    @endphp

                    <article class="card">

                        <div class="request-overview">

                            <!-- FOTOGRAFÍA DEL OBJETO -->

                            <div class="request-photo">

                                @if ($solicitud->objetoEncontrado?->imagen)

                                    <img
                                        src="{{ asset('storage/' . $solicitud->objetoEncontrado->imagen) }}"
                                        alt="Fotografía de {{ $solicitud->objetoEncontrado->titulo }}"
                                        loading="lazy"
                                    >

                                @else

                                    Sin fotografía

                                @endif

                            </div>

                            <!-- INFORMACIÓN DE LA SOLICITUD -->

                            <div class="request-details">

                                <h2>
                                    {{ $solicitud->objetoEncontrado?->titulo ?? 'Objeto no disponible' }}
                                </h2>

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
                                    <strong>Número de solicitud:</strong>
                                    #{{ $solicitud->id }}
                                </p>

                            </div>

                            <!-- ESTADO Y CHAT -->

                            <div class="request-actions">

                                <p class="status-title">
                                    Estado de la solicitud
                                </p>

                                @if ($recuperacionCompleta)

                                    <span class="estado recuperada">
                                        Recuperado
                                    </span>

                                @else

                                    <span class="estado {{ $solicitud->estado }}">
                                        {{ ucfirst($solicitud->estado) }}
                                    </span>

                                @endif

                                @if ($solicitud->estado === 'aceptada')

                                    <p class="texto-aceptada">
                                        ✓ Solicitud aceptada.
                                        Puedes comunicarte con la persona
                                        que encontró el objeto para
                                        coordinar su entrega.
                                    </p>

                                    <a
                                        href="{{ route('chat.mostrar', $solicitud->id) }}"
                                        class="btn-chat"
                                    >
                                        <svg class="icon">
                                            <use href="#i-chat"/>
                                        </svg>
                                        Abrir chat
                                    </a>

                                @elseif ($solicitud->estado === 'rechazada')

                                    <p class="texto-rechazada">
                                        Esta solicitud fue rechazada.
                                    </p>

                                @endif

                            </div>

                        </div>

                        <!-- ESTADO DE RECUPERACIÓN -->

                        @if ($solicitud->estado === 'aceptada')

                            <div class="recuperacion">

                                <h3>Estado de recuperación</h3>

                                <p class="recuperacion-descripcion">
                                    Para completar la recuperación,
                                    ambas personas deben confirmar
                                    la entrega y recepción del objeto.
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

                                @elseif (!$recepcionConfirmada)

                                    <form
                                        method="POST"
                                        action="{{ route('solicitudes.confirmar-recepcion', $solicitud->id) }}"
                                        onsubmit="return confirm('¿Confirmas que ya recibiste físicamente este objeto y verificaste que te corresponde?');"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn-confirmar"
                                        >
                                            ✓ Confirmar que recibí el objeto
                                        </button>
                                    </form>

                                @else

                                    <div class="aviso-espera">
                                        ✓ Ya confirmaste la recepción.
                                        Estamos esperando que la otra persona
                                        confirme la entrega.
                                    </div>

                                @endif

                                <p class="nota-confirmacion">
                                    Confirma únicamente cuando hayas recibido
                                    físicamente el objeto y verificado
                                    que te corresponde.
                                </p>

                            </div>

                        @endif

                    </article>

                @empty

                    <div class="empty">

                        <h2>Aún no tienes solicitudes.</h2>

                        <p>
                            Cuando solicites recuperar un objeto,
                            podrás consultar aquí el estado
                            de tu solicitud.
                        </p>

                    </div>

                @endforelse

                <div class="pagination">
                    {{ $solicitudes->links() }}
                </div>

            </main>

        </div>

    </div>

    <!-- MENÚ EN CELULARES -->

    <script>
        const menuToggle =
            document.getElementById('menuToggle');

        const sidebar =
            document.getElementById('sidebar');

        const overlay =
            document.getElementById('overlay');

        function cerrarMenu() {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');

            menuToggle.setAttribute(
                'aria-expanded',
                'false'
            );
        }

        menuToggle.addEventListener('click', () => {
            const abierto = sidebar.classList.toggle('open');

            overlay.classList.toggle('open', abierto);

            menuToggle.setAttribute(
                'aria-expanded',
                String(abierto)
            );
        });

        overlay.addEventListener('click', cerrarMenu);

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                cerrarMenu();
            }
        });
    </script>

    <!-- ACTUALIZACIÓN AUTOMÁTICA ORIGINAL -->

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
