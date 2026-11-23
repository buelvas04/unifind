
@php
    $usuarioActual = auth()->user();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Explorar objetos encontrados | UniFind</title>

    <style>
        :root {
            --azul: #1763d4;
            --azul-oscuro: #0e2b51;
            --texto: #132b4b;
            --secundario: #61748f;
            --fondo: #f1f5fb;
            --borde: #e0e9f5;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: var(--texto);
            background: var(--fondo);
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button, input, select {
            font: inherit;
        }

        button {
            cursor: pointer;
        }

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
            padding: 25px 14px 18px;
            background: linear-gradient(
                155deg,
                #153b6b,
                #0d274b 65%,
                #0a2141
            );
            color: #e9f2ff;
            display: flex;
            flex-direction: column;
            z-index: 30;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 4px 11px 27px;
            color: white;
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
            background: rgba(255,255,255,.11);
        }

        .nav-link.active {
            color: white;
            background: linear-gradient(
                90deg,
                #287ee8,
                #1760cd
            );
        }

        .nav-divider {
            border-top: 1px solid rgba(211,230,255,.22);
            margin: 14px 10px;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 25px;
        }

        .logout-form {
            margin: 0;
        }

        .logout {
            display: flex;
            align-items: center;
            gap: 13px;
            width: 100%;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,.18);
            background: rgba(255,255,255,.09);
            padding: 12px 14px;
            color: white;
            font-weight: 600;
            text-align: left;
        }

        .logout:hover {
            background: rgba(255,255,255,.18);
        }

        /* BARRA SUPERIOR */

        .workspace {
            min-width: 0;
        }

        .topbar {
            background: white;
            min-height: 80px;
            border-bottom: 1px solid var(--borde);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 14px 32px;
        }

        .topbar-search {
            flex: 1;
            position: relative;
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
            height: 43px;
            width: 100%;
            padding: 0 15px 0 44px;
            border-radius: 10px;
            border: 1px solid #ccd9ea;
            background: #f8fbff;
            outline: none;
        }

        .topbar-search input:focus,
        .search input:focus,
        .report-select:focus {
            border-color: #397ae1;
            box-shadow: 0 0 0 3px rgba(57,122,225,.13);
            outline: none;
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
            background: #edf4ff;
            border-radius: 12px;
            color: #174b91;
        }

        .bell:hover {
            background: #dbeaff;
        }

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
            background: #e2edff;
            color: #145ab4;
            border-radius: 50%;
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
            background: #ecf3ff;
            color: #1557b6;
            border: none;
            border-radius: 9px;
            padding: 9px;
        }

        .overlay {
            display: none;
        }

        /* CONTENIDO PRINCIPAL */

        .main {
            max-width: 1440px;
            padding: 32px 34px 50px;
            margin: 0 auto;
        }

        .back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #dfecff;
            color: #1559bc;
            border-radius: 999px;
            padding: 9px 15px;
            font-weight: 700;
            font-size: 13px;
            margin-bottom: 15px;
        }

        .back:hover {
            background: #cfe2ff;
        }

        .back .icon {
            width: 17px;
            height: 17px;
        }

        h1 {
            font-size: clamp(23px, 2.2vw, 30px);
            margin: 0 0 8px;
            color: #0e2c51;
        }

        .intro {
            color: var(--secundario);
            line-height: 1.5;
            font-size: 15px;
            margin: 0 0 24px;
        }

        /* BUSCADOR */

        .search {
            display: flex;
            gap: 12px;
            margin: 0 0 22px;
        }

        .search input {
            flex: 1;
            min-width: 0;
            padding: 14px 16px;
            background: white;
            border: 1px solid #cbd9eb;
            border-radius: 11px;
        }

        .search button,
        .request-button {
            background: #1763d4;
            color: white;
            border: 0;
            border-radius: 10px;
            font-weight: 700;
            padding: 13px 23px;
        }

        .search button:hover,
        .request-button:hover {
            background: #1052b8;
        }

        /* MENSAJES */

        .alert {
            padding: 15px 18px;
            border-radius: 11px;
            margin: 16px 0;
            line-height: 1.5;
            font-size: 14px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #9adeb6;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #efb2b2;
        }

        .alert ul {
            margin: 8px 0 0;
            padding-left: 20px;
        }

        /* TARJETAS HORIZONTALES */

        .grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 20px;
        }

        .card {
            display: grid;
            grid-template-columns:
                minmax(190px, 260px)
                minmax(0, 1fr)
                minmax(245px, 315px);
            align-items: start;
            gap: 22px;
            background: white;
            border: 1px solid var(--borde);
            box-shadow: 0 8px 25px rgba(22,65,118,.08);
            border-radius: 18px;
            padding: 18px;
            min-width: 0;
        }

        /* FOTOGRAFÍA COMPLETA */

        .photo-box,
        .no-image {
            width: 100%;
            height: 220px;
            background: #f1f5f9;
            border-radius: 13px;
            overflow: hidden;
        }

        .photo-box img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
        }

        .no-image {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #54739b;
            text-align: center;
            font-size: 14px;
            font-weight: 700;
        }

        /* INFORMACIÓN DEL OBJETO */

        .details {
            min-width: 0;
        }

        .details h2 {
            font-size: 23px;
            margin: 4px 0 16px;
            color: #1261c8;
            overflow-wrap: anywhere;
        }

        .details p {
            font-size: 14px;
            line-height: 1.6;
            margin: 10px 0;
            overflow-wrap: anywhere;
        }

        .details strong {
            color: #19385e;
        }

        .tag {
            display: inline-block;
            margin-top: 9px;
            padding: 7px 12px;
            border-radius: 999px;
            color: #166534;
            background: #dcfce7;
            font-size: 12px;
            font-weight: 800;
        }

        /* SOLICITUDES DE RECUPERACIÓN */

        .request-panel {
            min-width: 0;
            border-left: 1px solid #e2eaf5;
            padding-left: 22px;
            align-self: stretch;
        }

        .request-panel h3 {
            font-size: 16px;
            margin: 3px 0 10px;
            color: #15365e;
        }

        .request-panel p {
            font-size: 13px;
            line-height: 1.55;
            color: #556984;
            overflow-wrap: anywhere;
        }

        .report-label {
            display: block;
            color: #243f62;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.5;
            margin: 12px 0 9px;
        }

        .report-select {
            width: 100%;
            max-width: 100%;
            min-width: 0;
            border: 1px solid #cbd9e9;
            border-radius: 9px;
            background: white;
            padding: 12px;
            color: #19385e;
            font-size: 13px;
        }

        .report-help {
            background: #edf5ff;
            color: #22518a;
            border: 1px solid #d8e9ff;
            border-radius: 10px;
            padding: 11px 12px;
            margin: 13px 0;
            font-size: 12px;
            line-height: 1.5;
        }

        .report-help summary {
            font-weight: 700;
            cursor: pointer;
        }

        .report-help p {
            color: #22518a;
            margin: 9px 0 0;
        }

        .request-button {
            width: 100%;
            margin-top: 12px;
        }

        .owner-note {
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: start;
            background: #f3f7fd;
            border-radius: 12px;
            padding: 15px;
        }

        .owner-note strong {
            color: #1b528e;
        }

        .owner-note p {
            margin: 0;
        }

        /* ESTADO VACÍO Y PAGINACIÓN */

        .empty {
            background: white;
            border: 1px solid var(--borde);
            box-shadow: 0 8px 25px rgba(22,65,118,.06);
            border-radius: 16px;
            padding: 42px 24px;
            text-align: center;
        }

        .empty h2 {
            color: #153c6d;
            margin-top: 0;
        }

        .empty p {
            color: var(--secundario);
        }

        .pagination {
            margin-top: 25px;
        }

        /* TABLET */

        @media (max-width: 1180px) {
            .card {
                grid-template-columns:
                    minmax(180px, 240px)
                    minmax(0, 1fr);
            }

            .request-panel {
                grid-column: 1 / -1;
                border-left: none;
                border-top: 1px solid #e2eaf5;
                padding: 16px 0 0;
            }
        }

        /* CELULAR */

        @media (max-width: 760px) {
            .app {
                display: block;
            }

            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                width: 255px;
                transform: translateX(-100%);
                transition: transform .2s;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .overlay.open {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(6,24,48,.55);
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

            .user-name {
                display: none;
            }

            .topbar-actions {
                gap: 9px;
            }

            .main {
                padding: 25px 15px 35px;
            }

            .search {
                flex-direction: column;
            }

            .search button {
                width: 100%;
            }

            .card {
                grid-template-columns: 1fr;
                padding: 13px;
                gap: 15px;
            }

            .photo-box,
            .no-image {
                height: 240px;
            }

            .request-panel {
                grid-column: auto;
            }

            .details h2 {
                margin-top: 0;
            }
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
    </svg>

    <div class="overlay" id="overlay"></div>

    <div class="app">

        <!-- MENÚ LATERAL -->

        <aside
            class="sidebar"
            id="sidebar"
            aria-label="Navegación principal"
        >
            <a class="brand" href="{{ route('dashboard') }}">
                <span class="brand-icon">
                    <svg class="icon"><use href="#i-logo"/></svg>
                </span>
                <span>UniFind</span>
            </a>

            <nav class="nav">

                <a
                    class="nav-link"
                    href="{{ route('dashboard') }}"
                >
                    <svg class="icon"><use href="#i-home"/></svg>
                    Inicio
                </a>

                <a
                    class="nav-link active"
                    href="{{ route('objetos-encontrados.explorar') }}"
                    aria-current="page"
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
                    class="nav-link"
                    href="{{ route('solicitudes.index') }}"
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

        <!-- CONTENIDO PRINCIPAL -->

        <div class="workspace">

            <!-- BARRA SUPERIOR -->

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
                        value="{{ $busqueda }}"
                        placeholder="Buscar celular, computador, llaves..."
                        aria-label="Buscar objetos encontrados"
                    >
                </form>

                <div class="topbar-actions">

                    <a
                        class="bell"
                        href="{{ route('notificaciones.index') }}"
                        title="Ver notificaciones"
                        aria-label="Ver notificaciones"
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

            <main class="main">

                <!-- VOLVER AL PANEL -->

                <a
                    class="back"
                    href="{{ route('dashboard') }}"
                >
                    <svg class="icon">
                        <use href="#i-arrow-left"/>
                    </svg>
                    Volver al panel
                </a>

                <h1>Explorar objetos encontrados</h1>

                <p class="intro">
                    Consulta los objetos encontrados dentro de la universidad.
                    Utiliza el buscador para encontrar lo que necesitas.
                </p>

                <!-- MENSAJES DEL SISTEMA -->

                @if (session('success'))
                    <div class="alert alert-success" role="status">
                        <strong>Solicitud procesada correctamente.</strong>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error" role="alert">
                        <strong>No se pudo completar la solicitud.</strong>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-error" role="alert">
                        <strong>Revisa la información ingresada:</strong>

                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- BUSCADOR -->

                <form
                    method="GET"
                    action="{{ route('objetos-encontrados.explorar') }}"
                    class="search"
                >
                    <input
                        type="search"
                        name="buscar"
                        value="{{ $busqueda }}"
                        placeholder="Buscar celular, computador, llaves..."
                        aria-label="Buscar objetos encontrados"
                    >

                    <button type="submit">Buscar</button>
                </form>

                <!-- LISTADO DE OBJETOS -->

                @if ($objetos->isEmpty())

                    <div class="empty">
                        <h2>No se encontraron objetos.</h2>

                        <p>
                            Prueba con otra palabra o consulta más adelante.
                        </p>
                    </div>

                @else

                    <div class="grid">

                        @foreach ($objetos as $objeto)

                            <article class="card">

                                <!-- IMAGEN -->

                                @if ($objeto->imagen)

                                    <div class="photo-box">
                                        <img
                                            src="{{ asset('storage/' . $objeto->imagen) }}"
                                            alt="Fotografía de {{ $objeto->titulo }}"
                                            loading="lazy"
                                        >
                                    </div>

                                @else

                                    <div class="no-image">
                                        Sin fotografía
                                    </div>

                                @endif

                                <!-- INFORMACIÓN -->

                                <div class="details">

                                    <h2>{{ $objeto->titulo }}</h2>

                                    <p>
                                        <strong>Categoría:</strong>
                                        {{ $objeto->categoria?->nombre ?? 'Sin categoría' }}
                                    </p>

                                    <p>
                                        <strong>Descripción:</strong>
                                        {{ $objeto->descripcion }}
                                    </p>

                                    <p>
                                        <strong>Lugar donde fue encontrado:</strong>
                                        {{ $objeto->ubicacion }}
                                    </p>

                                    <p>
                                        <strong>Fecha del hallazgo:</strong>
                                        {{ $objeto->fecha_encontrado?->format('d/m/Y') }}
                                    </p>

                                    <span class="tag">
                                        {{ ucfirst($objeto->estado) }}
                                    </span>

                                </div>

                                <!-- SOLICITAR RECUPERACIÓN -->

                                <div class="request-panel">

                                    @if ((int) $objeto->usuario_id !== (int) auth()->id())

                                        <h3>
                                            ¿Crees que este objeto es tuyo?
                                        </h3>

                                        <p>
                                            Envía una solicitud para comunicarte
                                            con la persona que lo encontró.
                                        </p>

                                        <form
                                            action="{{ route('solicitudes.store', $objeto->id) }}"
                                            method="POST"
                                        >
                                            @csrf

                                            <label
                                                for="objeto_perdido_{{ $objeto->id }}"
                                                class="report-label"
                                            >
                                                ¿Ya reportaste este objeto como perdido?
                                            </label>

                                            <select
                                                id="objeto_perdido_{{ $objeto->id }}"
                                                name="objeto_perdido_id"
                                                class="report-select"
                                            >
                                                <option value="">
                                                    No tengo un reporte de pérdida para este objeto
                                                </option>

                                                @foreach ($objetosPerdidos as $perdido)

                                                    <option
                                                        value="{{ $perdido->id }}"
                                                        @selected(
                                                            old('objeto_perdido_id') == $perdido->id
                                                        )
                                                    >
                                                        {{ $perdido->titulo }}
                                                        —
                                                        {{ $perdido->lugar_perdida }}
                                                    </option>

                                                @endforeach

                                            </select>

                                            @if ($objetosPerdidos->isNotEmpty())

                                                <details class="report-help">

                                                    <summary>
                                                        Información importante sobre tu reporte
                                                    </summary>

                                                    <p>
                                                        Si este objeto corresponde a uno
                                                        que ya reportaste como perdido,
                                                        selecciónalo en la lista.

                                                        Cuando ambas personas confirmen
                                                        la entrega y la recepción,
                                                        UniFind podrá marcar también
                                                        tu reporte como recuperado.

                                                        Si ninguno corresponde,
                                                        deja seleccionada la primera opción.
                                                    </p>

                                                </details>

                                            @else

                                                <div class="report-help">
                                                    No tienes reportes de pérdida activos.

                                                    Puedes solicitar la recuperación
                                                    sin vincular un reporte.

                                                    En ese caso, cuando ambas personas
                                                    confirmen la recuperación,
                                                    se actualizará el estado
                                                    del objeto encontrado.
                                                </div>

                                            @endif

                                            <button
                                                type="submit"
                                                class="request-button"
                                            >
                                                Solicitar recuperación
                                            </button>

                                        </form>

                                    @else

                                        <div class="owner-note">

                                            <strong>
                                                Publicado por ti
                                            </strong>

                                            <p>
                                                Este objeto fue publicado por ti.

                                                Las solicitudes que recibas
                                                se gestionan desde
                                                «Solicitudes recibidas».
                                            </p>

                                            <a
                                                class="back"
                                                style="margin: 4px 0 0;"
                                                href="{{ route('solicitudes.recibidas') }}"
                                            >
                                                Ver solicitudes recibidas
                                            </a>

                                        </div>

                                    @endif

                                </div>

                            </article>

                        @endforeach

                    </div>

                    <!-- PAGINACIÓN -->

                    <div class="pagination">
                        {{ $objetos->links() }}
                    </div>

                @endif

            </main>

        </div>

    </div>

    <!-- MENÚ PARA CELULARES -->

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

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

            overlay.classList.toggle(
                'open',
                abierto
            );

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

</body>
</html>
