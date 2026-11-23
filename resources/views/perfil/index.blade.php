
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mi perfil y configuración | UniFind</title>

    <style>
        :root {
            --nav: #102b50;
            --nav2: #153f73;
            --blue: #1b63d5;
            --ink: #122848;
            --muted: #627591;
            --border: #e1e9f5;
            --bg: #f1f5fb;
            --shadow: 0 8px 24px rgba(26,68,120,.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg);
            font-family: 'Segoe UI', Arial, sans-serif;
            color: var(--ink);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button, input, select {
            font: inherit;
        }

        button {
            cursor: pointer;
        }

        .layout {
            display: grid;
            grid-template-columns: 245px minmax(0, 1fr);
            min-height: 100vh;
        }

        .sidebar {
            background: linear-gradient(
                160deg,
                var(--nav2),
                var(--nav)
            );
            color: white;
            padding: 27px 14px;
            min-height: 100vh;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 27px;
            font-weight: 800;
            padding: 0 12px 32px;
        }

        .brand-icon {
            display: grid;
            place-items: center;
            background: #287bf0;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            font-size: 22px;
        }

        .side-nav {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .side-nav a,
        .side-nav button {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 13px 14px;
            border-radius: 11px;
            border: 0;
            background: transparent;
            color: #e8f2ff;
            font-weight: 600;
            font-size: 14px;
            text-align: left;
        }

        .side-nav a:hover,
        .side-nav button:hover {
            background: rgba(255,255,255,.11);
        }

        .side-nav .active {
            background: #2575e0;
            color: white;
        }

        .side-nav .glyph {
            font-size: 19px;
            width: 23px;
            text-align: center;
        }

        .side-nav hr {
            border: 0;
            border-top: 1px solid #ffffff30;
            width: 100%;
            margin: 14px 0;
        }

        .side-nav form {
            margin-top: 8px;
        }

        .workspace {
            min-width: 0;
        }

        .topbar {
            height: 77px;
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 0 33px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .topbar strong {
            font-size: 17px;
        }

        .topbar small {
            color: var(--muted);
            display: block;
            margin-top: 3px;
        }

        .top-link {
            font-size: 14px;
            color: #175abb;
            font-weight: 700;
            background: #ecf4ff;
            padding: 11px 15px;
            border-radius: 9px;
            white-space: nowrap;
        }

        main {
            width: min(1280px, 100%);
            margin: 0 auto;
            padding: 35px 33px 55px;
        }

        h1 {
            margin: 0;
            font-size: 30px;
            letter-spacing: -.7px;
        }

        .subtitle {
            color: var(--muted);
            margin: 8px 0 27px;
        }

        .flash {
            background: #e2f9ea;
            border: 1px solid #a8e3bd;
            color: #176239;
            padding: 14px 18px;
            border-radius: 11px;
            margin-bottom: 15px;
        }

        .errors {
            background: #fff0ef;
            border: 1px solid #f1b8b5;
            color: #9d2722;
            padding: 14px 18px;
            border-radius: 11px;
            margin-bottom: 17px;
        }

        .errors ul {
            margin: 9px 0 0;
            padding-left: 19px;
        }

        .tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin-bottom: 19px;
        }

        .tab {
            border: 1px solid #d9e4f4;
            background: white;
            padding: 13px 17px;
            border-radius: 11px;
            font-weight: 700;
            color: #395472;
        }

        .tab.active {
            color: #1059c8;
            background: #e6f0ff;
            border-color: #aacbfa;
            box-shadow: 0 3px 9px #124b9512;
        }

        .tab.danger {
            color: #c92b2b;
        }

        .tab.danger.active {
            background: #fff0f0;
            border-color: #f4c0c0;
        }

        .panel {
            display: none;
        }

        .panel.active {
            display: block;
        }

        .columns {
            display: grid;
            grid-template-columns:
                minmax(245px, 330px)
                minmax(0, 1fr);
            gap: 21px;
            align-items: start;
        }

        .card {
            background: white;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            border-radius: 16px;
            padding: 24px;
        }

        .cover {
            height: 100px;
            margin: -24px -24px 0;
            border-radius: 16px 16px 0 0;
            background: linear-gradient(
                120deg,
                #d4e8ff,
                #e8f3ff
            );
            position: relative;
            overflow: hidden;
        }

        .cover:before {
            content: '';
            position: absolute;
            border: 12px solid #ffffff70;
            width: 130px;
            height: 130px;
            transform: rotate(30deg);
            top: -90px;
            right: 25px;
            border-radius: 20px;
        }

        .photo {
            width: 112px;
            height: 112px;
            border-radius: 50%;
            border: 5px solid white;
            background: #dcecff;
            display: grid;
            place-items: center;
            color: #155ab6;
            font-size: 49px;
            font-weight: 800;
            overflow: hidden;
            margin: -55px auto 12px;
            position: relative;
            box-shadow: 0 3px 16px #1557a329;
        }

        .photo img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .person {
            text-align: center;
            font-size: 20px;
            font-weight: 800;
            margin: 0 0 5px;
            overflow-wrap: anywhere;
        }

        .role {
            display: block;
            width: max-content;
            max-width: 100%;
            margin: 0 auto 13px;
            background: #e1edff;
            color: #145ac5;
            border-radius: 100px;
            padding: 6px 13px;
            font-size: 12px;
            font-weight: 700;
        }

        .person-detail {
            text-align: center;
            color: #60728c;
            font-size: 13px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .photo-controls {
            border-top: 1px solid var(--border);
            padding-top: 17px;
            margin-top: 20px;
        }

        .photo-controls p {
            margin: 0 0 11px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.6;
        }

        .upload-input {
            max-width: 100%;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .card h2 {
            font-size: 21px;
            margin: 0 0 6px;
        }

        .helper {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.5;
            margin: 0 0 23px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(
                2,
                minmax(0, 1fr)
            );
            gap: 17px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 7px;
            margin-bottom: 15px;
        }

        .field label {
            font-size: 13px;
            font-weight: 700;
        }

        .field input {
            width: 100%;
            background: #f9fbff;
            border: 1px solid #cfdced;
            border-radius: 9px;
            padding: 13px;
            outline: none;
            color: var(--ink);
        }

        .field input:focus {
            border-color: #2b72dd;
            box-shadow: 0 0 0 3px #287de322;
        }

        .field .locked {
            background: #eff4fb;
            color: #536780;
        }

        .full {
            grid-column: 1 / -1;
        }

        .actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .btn {
            border: 0;
            border-radius: 10px;
            padding: 12px 19px;
            font-weight: 700;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            color: white;
            background: var(--blue);
        }

        .btn-primary:hover {
            background: #104fae;
        }

        .btn-light {
            background: #eef4fc;
            color: #245996;
        }

        .btn-light:hover {
            background: #e1ecf9;
        }

        .btn-danger {
            background: #d53434;
            color: white;
        }

        .btn-danger:hover {
            background: #b51e1e;
        }

        .note {
            border-radius: 11px;
            background: #eef6ff;
            color: #245184;
            font-size: 13px;
            line-height: 1.6;
            padding: 13px 16px;
            margin-bottom: 18px;
        }

        .warning {
            background: #fff0ef;
            color: #9e2721;
            border: 1px solid #f0c0be;
        }

        .checkrow {
            display: flex;
            align-items: start;
            gap: 9px;
            font-size: 14px;
            margin: 15px 0;
        }

        .checkrow input {
            margin-top: 3px;
        }

        @media (max-width: 980px) {
            .layout {
                grid-template-columns:
                    190px minmax(0, 1fr);
            }

            .sidebar {
                padding: 22px 10px;
            }

            .brand {
                font-size: 23px;
            }

            .columns {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 680px) {
            .layout {
                display: block;
            }

            .sidebar {
                min-height: auto;
                padding: 13px;
            }

            .brand {
                padding: 3px 6px 12px;
            }

            .side-nav {
                flex-direction: row;
                overflow-x: auto;
                gap: 3px;
            }

            .side-nav a,
            .side-nav button {
                white-space: nowrap;
                padding: 10px 11px;
            }

            .side-nav hr,
            .side-nav form {
                display: none;
            }

            .topbar {
                height: auto;
                padding: 14px 15px;
            }

            main {
                padding: 25px 14px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .tabs {
                gap: 6px;
            }

            .tab {
                padding: 11px 12px;
                font-size: 12px;
            }

            h1 {
                font-size: 26px;
            }

            .card {
                padding: 18px;
            }

            .cover {
                margin: -18px -18px 0;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    <aside class="sidebar">

        <a href="{{ route('dashboard') }}" class="brand">
            <span class="brand-icon">⌕</span>
            UniFind
        </a>

        <nav class="side-nav" aria-label="Navegación">

            <a href="{{ route('dashboard') }}">
                <span class="glyph">⌂</span>
                Inicio
            </a>

            <a href="{{ route('objetos-encontrados.explorar') }}">
                <span class="glyph">⌕</span>
                Explorar objetos
            </a>

            <a href="{{ route('objetos-perdidos.index') }}">
                <span class="glyph">▤</span>
                Mis objetos perdidos
            </a>

            <a href="{{ route('objetos-encontrados.index') }}">
                <span class="glyph">▣</span>
                Mis objetos encontrados
            </a>

            <a href="{{ route('solicitudes.index') }}">
                <span class="glyph">☑</span>
                Mis solicitudes
            </a>

            <a href="{{ route('notificaciones.index') }}">
                <span class="glyph">♧</span>
                Notificaciones
            </a>

            <hr>

            <a
                href="{{ route('perfil.index') }}"
                class="active"
                aria-current="page"
            >
                <span class="glyph">♙</span>
                Mi perfil
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit">
                    <span class="glyph">↪</span>
                    Cerrar sesión
                </button>
            </form>

        </nav>

    </aside>

    <div class="workspace">

        <header class="topbar">

            <div>
                <strong>UniFind</strong>
                <small>
                    Tu espacio universitario de objetos
                    perdidos y encontrados
                </small>
            </div>

            <a href="{{ route('dashboard') }}" class="top-link">
                ← Volver al inicio
            </a>

        </header>

        <main>

            <h1>Mi perfil y configuración</h1>

            <p class="subtitle">
                Administra tus datos personales,
                tu fotografía y la seguridad de tu cuenta.
            </p>

            @if(session('success'))
                <div class="flash" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="errors" role="alert">
                    <strong>Revisa lo siguiente:</strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- PESTAÑAS DEL PERFIL -->

            <div
                class="tabs"
                role="tablist"
                aria-label="Opciones del perfil"
            >

                <button
                    type="button"
                    class="tab"
                    data-tab="datos"
                    role="tab"
                >
                    Información personal
                </button>

                <button
                    type="button"
                    class="tab"
                    data-tab="correo"
                    role="tab"
                >
                    Cambiar correo
                </button>

                <button
                    type="button"
                    class="tab"
                    data-tab="contrasena"
                    role="tab"
                >
                    Contraseña
                </button>

                <button
                    type="button"
                    class="tab danger"
                    data-tab="eliminar"
                    role="tab"
                >
                    Eliminar cuenta
                </button>

            </div>

            <!-- INFORMACIÓN PERSONAL -->

            <section
                class="panel"
                id="tab-datos"
                role="tabpanel"
            >

                <div class="columns">

                    <!-- TARJETA DEL USUARIO -->

                    <div class="card identity">

                        <div class="cover"></div>

                        <div class="photo">

                            @if($usuario->foto_perfil)

                                <img
                                    src="{{ asset('storage/' . $usuario->foto_perfil) }}"
                                    alt="Foto de perfil de {{ $usuario->name }}"
                                >

                            @else

                                {{ strtoupper(mb_substr(trim($usuario->name), 0, 1)) }}

                            @endif

                        </div>

                        <p class="person">
                            {{ $usuario->name }}
                        </p>

                        <span class="role">
                            {{ $usuario->rol?->nombre ?? 'Usuario' }}
                        </span>

                        <div class="person-detail">

                            {{ $usuario->programa ?: 'Sin programa registrado' }}

                            <br>

                            {{ $usuario->facultad ?: 'Facultad sin registrar' }}

                            <br>

                            {{ $usuario->email }}

                        </div>

                        <!-- SUBIR FOTOGRAFÍA -->

                        <div class="photo-controls">

                            <p>
                                Foto de perfil: JPG, PNG o WebP.
                                Tamaño máximo: 2 MB.
                            </p>

                            <form
                                method="POST"
                                action="{{ route('perfil.foto') }}"
                                enctype="multipart/form-data"
                            >
                                @csrf

                                <input
                                    type="hidden"
                                    name="seccion"
                                    value="datos"
                                >

                                <input
                                    class="upload-input"
                                    type="file"
                                    name="foto"
                                    accept="image/jpeg,image/png,image/webp"
                                    required
                                    aria-label="Seleccionar foto de perfil"
                                >

                                <button
                                    class="btn btn-primary"
                                    type="submit"
                                >
                                    Guardar fotografía
                                </button>

                            </form>

                            @if($usuario->foto_perfil)

                                <form
                                    method="POST"
                                    action="{{ route('perfil.foto.eliminar') }}"
                                    style="margin-top:10px"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="btn btn-light"
                                        type="submit"
                                    >
                                        Quitar foto
                                    </button>

                                </form>

                            @endif

                        </div>

                    </div>

                    <!-- EDITAR DATOS -->

                    <div class="card">

                        <h2>Información personal</h2>

                        <p class="helper">
                            Actualiza tu información cuando cambie.
                            Tu rol universitario no puede modificarse
                            desde esta página.
                        </p>

                        <form
                            method="POST"
                            action="{{ route('perfil.datos') }}"
                        >
                            @csrf
                            @method('PUT')

                            <input
                                type="hidden"
                                name="seccion"
                                value="datos"
                            >

                            <div class="form-grid">

                                <div class="field full">

                                    <label for="name">
                                        Nombre completo
                                    </label>

                                    <input
                                        id="name"
                                        name="name"
                                        type="text"
                                        value="{{ old('name', $usuario->name) }}"
                                        required
                                        maxlength="255"
                                        autocomplete="name"
                                    >

                                </div>

                                <div class="field">

                                    <label for="telefono">
                                        Teléfono (opcional)
                                    </label>

                                    <input
                                        id="telefono"
                                        name="telefono"
                                        type="tel"
                                        value="{{ old('telefono', $usuario->telefono) }}"
                                        maxlength="30"
                                        autocomplete="tel"
                                    >

                                </div>

                                <div class="field">

                                    <label for="rol">
                                        Tipo de usuario
                                    </label>

                                    <input
                                        id="rol"
                                        class="locked"
                                        value="{{ $usuario->rol?->nombre ?? 'Usuario' }}"
                                        disabled
                                    >

                                </div>

                                <div class="field">

                                    <label for="facultad">
                                        Facultad
                                    </label>

                                    <input
                                        id="facultad"
                                        name="facultad"
                                        type="text"
                                        value="{{ old('facultad', $usuario->facultad) }}"
                                        maxlength="120"
                                    >

                                </div>

                                <div class="field">

                                    <label for="programa">
                                        Programa académico
                                    </label>

                                    <input
                                        id="programa"
                                        name="programa"
                                        type="text"
                                        value="{{ old('programa', $usuario->programa) }}"
                                        maxlength="120"
                                    >

                                </div>

                                <div class="field full">

                                    <label for="correo_actual">
                                        Correo electrónico
                                    </label>

                                    <input
                                        id="correo_actual"
                                        class="locked"
                                        value="{{ $usuario->email }}"
                                        disabled
                                    >

                                </div>

                            </div>

                            <div class="actions">

                                <a
                                    href="{{ route('dashboard') }}"
                                    class="btn btn-light"
                                >
                                    Cancelar
                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Guardar cambios
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </section>

            <!-- CAMBIAR CORREO -->

            <section
                class="panel"
                id="tab-correo"
                role="tabpanel"
            >

                <div class="card" style="max-width:760px">

                    <h2>Cambiar correo electrónico</h2>

                    <p class="helper">
                        Este será el correo que utilizarás para
                        iniciar sesión en UniFind.
                    </p>

                    <div class="note">
                        Por seguridad, debes escribir tu contraseña
                        actual para confirmar el cambio.
                    </div>

                    <form
                        method="POST"
                        action="{{ route('perfil.correo') }}"
                    >
                        @csrf
                        @method('PUT')

                        <input
                            type="hidden"
                            name="seccion"
                            value="correo"
                        >

                        <div class="field">

                            <label for="email">
                                Nuevo correo electrónico
                            </label>

                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email', $usuario->email) }}"
                                maxlength="255"
                                required
                                autocomplete="email"
                            >

                        </div>

                        <div class="field">

                            <label for="correo_password">
                                Contraseña actual
                            </label>

                            <input
                                id="correo_password"
                                name="current_password"
                                type="password"
                                required
                                autocomplete="current-password"
                            >

                        </div>

                        <div class="actions">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Actualizar correo
                            </button>

                        </div>

                    </form>

                </div>

            </section>

            <!-- CAMBIAR CONTRASEÑA -->

            <section
                class="panel"
                id="tab-contrasena"
                role="tabpanel"
            >

                <div class="card" style="max-width:760px">

                    <h2>Cambiar contraseña</h2>

                    <p class="helper">
                        Elige una contraseña diferente de la anterior,
                        con al menos ocho caracteres.
                    </p>

                    <form
                        method="POST"
                        action="{{ route('perfil.contrasena') }}"
                    >
                        @csrf
                        @method('PUT')

                        <input
                            type="hidden"
                            name="seccion"
                            value="contrasena"
                        >

                        <div class="field">

                            <label for="password_actual">
                                Contraseña actual
                            </label>

                            <input
                                id="password_actual"
                                type="password"
                                name="current_password"
                                required
                                autocomplete="current-password"
                            >

                        </div>

                        <div class="field">

                            <label for="password_nuevo">
                                Nueva contraseña
                            </label>

                            <input
                                id="password_nuevo"
                                type="password"
                                name="password"
                                required
                                minlength="8"
                                autocomplete="new-password"
                            >

                        </div>

                        <div class="field">

                            <label for="password_confirmacion">
                                Confirmar nueva contraseña
                            </label>

                            <input
                                id="password_confirmacion"
                                type="password"
                                name="password_confirmation"
                                required
                                minlength="8"
                                autocomplete="new-password"
                            >

                        </div>

                        <div class="actions">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Guardar contraseña
                            </button>

                        </div>

                    </form>

                </div>

            </section>

            <!-- ELIMINAR CUENTA -->

            <section
                class="panel"
                id="tab-eliminar"
                role="tabpanel"
            >

                <div class="card" style="max-width:760px">

                    <h2>Eliminar cuenta</h2>

                    <p class="helper">
                        Esta acción es permanente para las cuentas
                        que no tengan historial de actividad.
                    </p>

                    <div class="note warning">

                        <strong>Importante:</strong>

                        Si tienes publicaciones, solicitudes de
                        recuperación o mensajes, UniFind impedirá
                        eliminar tu cuenta automáticamente para
                        no borrar información de otras personas
                        ni romper el historial de entregas.

                    </div>

                    <form
                        method="POST"
                        action="{{ route('perfil.eliminar') }}"
                        onsubmit="return confirm('¿Confirmas que deseas eliminar tu cuenta? Esta acción no se puede deshacer.');"
                    >
                        @csrf
                        @method('DELETE')

                        <input
                            type="hidden"
                            name="seccion"
                            value="eliminar"
                        >

                        <div class="field">

                            <label for="eliminar_password">
                                Confirma tu contraseña
                            </label>

                            <input
                                id="eliminar_password"
                                name="current_password"
                                type="password"
                                required
                                autocomplete="current-password"
                            >

                        </div>

                        <label class="checkrow">

                            <input
                                type="checkbox"
                                name="confirmacion"
                                value="1"
                                required
                            >

                            Entiendo que esta acción es permanente
                            y quiero eliminar mi cuenta.

                        </label>

                        <div class="actions">

                            <button
                                class="btn btn-danger"
                                type="submit"
                            >
                                Eliminar mi cuenta
                            </button>

                        </div>

                    </form>

                </div>

            </section>

        </main>

    </div>

</div>

<script>
    const tabs = document.querySelectorAll('[data-tab]');
    const panels = document.querySelectorAll('.panel');

    function activarPestana(nombre) {

        if (!document.getElementById('tab-' + nombre)) {
            nombre = 'datos';
        }

        tabs.forEach(btn => {
            const activo = btn.dataset.tab === nombre;

            btn.classList.toggle('active', activo);
            btn.setAttribute('aria-selected', String(activo));
        });

        panels.forEach(panel => {
            panel.classList.toggle(
                'active',
                panel.id === 'tab-' + nombre
            );
        });
    }

    tabs.forEach(btn => {
        btn.addEventListener('click', () => {
            activarPestana(btn.dataset.tab);
        });
    });

    activarPestana(
        @json(old('seccion', session('perfil_tab', 'datos')))
    );
</script>

</body>
</html>

