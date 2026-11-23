
@php
    // Consultas de solo lectura para mostrar datos reales sin cambiar rutas ni controladores.
    $usuarioActual = auth()->user();

    $totalReportados = \App\Models\ObjetoPerdido::count()
        + \App\Models\ObjetoEncontrado::count();

    $totalRecuperados = \App\Models\ObjetoEncontrado::where(
        'estado', 'recuperado'
    )->count();

    $totalUsuarios = \App\Models\User::count();

    $misObjetosPerdidos = \App\Models\ObjetoPerdido::where(
        'usuario_id', $usuarioActual->id
    )->count();

    $misObjetosEncontrados = \App\Models\ObjetoEncontrado::where(
        'usuario_id', $usuarioActual->id
    )->count();

    $ultimosObjetos = \App\Models\ObjetoEncontrado::query()
        ->where('estado', 'disponible')
        ->orderByDesc('created_at')
        ->limit(3)
        ->get();
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Panel principal | UniFind</title>

    <style>
        :root {
            --azul: #155bd4;
            --azul-profundo: #102f58;
            --azul-marino: #0b2345;
            --texto: #122a49;
            --suave: #60758f;
            --borde: #e3ebf5;
            --fondo: #f1f5fb;
            --sombra: 0 8px 26px rgba(28, 65, 112, .07);
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
            text-decoration: none;
            color: inherit;
        }

        button,
        input {
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
            flex: none;
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
            display: flex;
            flex-direction: column;
            background: linear-gradient(
                158deg,
                #123967 0%,
                #0e2a50 55%,
                #0b2244 100%
            );
            color: #e5f0ff;
            overflow-y: auto;
overflow-x: hidden;
scrollbar-width: thin;
            z-index: 40;
            padding: 25px 14px 16px;
        }

        .brand {
            display: flex;
            gap: 11px;
            align-items: center;
            color: white;
            padding: 3px 12px 28px;
            font-size: 27px;
            font-weight: 800;
            letter-spacing: -.7px;
        }

        .brand-symbol {
            width: 40px;
            height: 40px;
            border-radius: 13px;
            display: grid;
            place-items: center;
            color: white;
            background: linear-gradient(
                135deg,
                #2f86ff,
                #1b56c6
            );
        }

        .brand-symbol .icon {
            width: 26px;
            height: 26px;
        }

        .nav-links {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 13px 15px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            transition: background .15s ease;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, .11);
        }

        .nav-link.active {
            color: white;
            background: linear-gradient(
                90deg,
                #287ee8,
                #1760cd
            );
            box-shadow: 0 4px 13px rgba(0, 0, 0, .09);
        }

        .nav-link.not-ready {
            opacity: .64;
            cursor: default;
        }

        .nav-link.not-ready:hover {
            background: transparent;
        }

        .nav-divider {
            margin: 14px 11px;
            border-top: 1px solid rgba(211, 230, 255, .2);
        }

        .sidebar-footer {
            margin-top: auto;
            position: relative;
            padding-top: 25px;
        }

        .sidebar-art {
            display: block;
            width: 100%;
            height: 100px;
            opacity: .34;
            color: #9abde9;
        }

        .logout-form {
            margin-top: 8px;
        }

        .logout {
            width: 100%;
            background: rgba(255, 255, 255, .08);
            color: white;
            border: 1px solid rgba(255, 255, 255, .16);
            padding: 12px 14px;
            border-radius: 10px;
            display: flex;
            gap: 12px;
            align-items: center;
            font-weight: 600;
            font-size: 14px;
        }

        .logout:hover {
            background: rgba(255, 255, 255, .17);
        }

        /* ÁREA PRINCIPAL */

        .workspace {
            min-width: 0;
        }

        .topbar {
            min-height: 83px;
            padding: 14px 34px;
            background: white;
            border-bottom: 1px solid var(--borde);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 22px;
        }

        .topbar-search {
            flex: 1;
            max-width: 610px;
            min-width: 180px;
            position: relative;
        }

        .topbar-search .icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6983a4;
        }

        .topbar-search input {
            width: 100%;
            height: 44px;
            padding: 0 15px 0 46px;
            border: 1px solid #cfdbeb;
            background: #f9fbfe;
            border-radius: 10px;
            outline: none;
        }

        .topbar-search input:focus,
        .search-form input:focus {
            border-color: #4786e8;
            box-shadow: 0 0 0 3px rgba(53, 118, 220, .13);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 19px;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 9px;
            min-width: 0;
        }

        .avatar {
            flex: none;
            width: 37px;
            height: 37px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #deecff;
            border: 2px solid #eff5ff;
            color: #1850a8;
            font-weight: 800;
        }

        .user-name {
            max-width: 170px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 14px;
            font-weight: 700;
        }

        .mobile-menu-btn {
            display: none;
            border: 0;
            border-radius: 9px;
            background: #ecf3ff;
            color: #144f9e;
            padding: 9px;
        }

        .page-content {
            max-width: 1380px;
            padding: 36px 35px 50px;
            margin: 0 auto;
        }

        /* BIENVENIDA */

        .welcome-top {
            display: flex;
            align-items: start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 22px;
        }

        .welcome-top h1 {
            font-size: clamp(24px, 2.2vw, 32px);
            letter-spacing: -.6px;
            margin: 0 0 7px;
            color: #0d2748;
        }

        .welcome-top p {
            margin: 0;
            color: var(--suave);
            font-size: 15px;
        }

        .role-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #1556bd;
            background: #e1ecff;
            border-radius: 999px;
            padding: 9px 15px;
            font-weight: 700;
            font-size: 13px;
        }

        /* BANNER PRINCIPAL */

        .hero {
            display: grid;
            grid-template-columns: minmax(0, 1.6fr) minmax(230px, .9fr);
            align-items: center;
            overflow: hidden;
            border-radius: 17px;
            margin-bottom: 22px;
            min-height: 178px;
            background: linear-gradient(
                115deg,
                #10478e 0%,
                #1b63d1 63%,
                #58a8ed 100%
            );
            box-shadow: 0 9px 25px rgba(29, 90, 188, .15);
            color: white;
        }

        .hero-copy {
            padding: 28px 32px;
        }

        .hero-copy .hero-eyebrow {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.2px;
            opacity: .83;
            text-transform: uppercase;
        }

        .hero-copy h2 {
            font-size: clamp(22px, 2.1vw, 31px);
            letter-spacing: -.4px;
            margin: 10px 0;
            line-height: 1.22;
        }

        .hero-copy p {
            margin: 0;
            line-height: 1.55;
            color: #e6f2ff;
            font-size: 14px;
            max-width: 440px;
        }

        .hero-graphic {
            height: 178px;
            align-self: stretch;
            position: relative;
            overflow: hidden;
        }

        .hero-graphic::before {
            content: '';
            position: absolute;
            width: 210px;
            height: 210px;
            border: 1px solid rgba(255, 255, 255, .21);
            border-radius: 50%;
            top: -64px;
            right: -27px;
        }

        .hero-graphic::after {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, .07);
            border-radius: 50%;
            bottom: -85px;
            left: -10px;
        }

        .hero-graphic svg {
            width: 100%;
            height: 100%;
        }

        /* ACCESOS RÁPIDOS */

        .section-title {
            font-size: 18px;
            margin: 25px 0 15px;
            color: #142b4b;
            letter-spacing: -.25px;
        }

        .quick-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 15px;
        }

        .quick-card {
            min-width: 0;
            min-height: 137px;
            padding: 20px;
            background: white;
            border: 1px solid var(--borde);
            border-radius: 15px;
            box-shadow: var(--sombra);
            display: flex;
            flex-direction: column;
            gap: 9px;
            transition: transform .15s, box-shadow .15s;
        }

        .quick-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(28, 65, 112, .12);
        }

        .quick-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .quick-icon {
            width: 47px;
            height: 47px;
            border-radius: 13px;
            display: grid;
            place-items: center;
            color: #1358c8;
            background: #e8f1ff;
        }

        .quick-icon.green {
            background: #e2f8eb;
            color: #0d9461;
        }

        .quick-icon.violet {
            background: #efebff;
            color: #6a4de4;
        }

        .quick-card strong {
            color: #102d55;
            font-size: 15px;
        }

        .quick-card span.desc {
            color: var(--suave);
            font-size: 13px;
            line-height: 1.42;
        }

        .quick-arrow {
            width: 20px;
            height: 20px;
            color: #2367cd;
        }

        /* BUSCADOR */

        .search-panel {
            margin-top: 19px;
            background: white;
            border: 1px solid var(--borde);
            box-shadow: var(--sombra);
            border-radius: 15px;
            padding: 21px 22px;
        }

        .search-panel h3 {
            margin: 0 0 13px;
            font-size: 17px;
        }

        .search-form {
            display: flex;
            gap: 12px;
        }

        .search-form input {
            flex: 1;
            min-width: 0;
            border: 1px solid #d1dceb;
            border-radius: 9px;
            padding: 12px 15px;
            outline: none;
        }

        .primary-btn {
            color: white;
            background: #1763d4;
            border: 0;
            border-radius: 9px;
            padding: 12px 23px;
            font-weight: 700;
        }

        .primary-btn:hover {
            background: #0f51bb;
        }

        /* PUBLICACIONES Y ESTADÍSTICAS */

        .bottom-grid {
            display: grid;
            grid-template-columns:
                minmax(0, 1.35fr)
                minmax(265px, 1fr);
            gap: 17px;
            margin-top: 20px;
        }

        .panel {
            background: white;
            border: 1px solid var(--borde);
            box-shadow: var(--sombra);
            border-radius: 15px;
            padding: 22px;
            min-width: 0;
        }

        .panel-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 15px;
        }

        .panel-title h3 {
            margin: 0;
            font-size: 17px;
        }

        .panel-title a {
            font-size: 12px;
            color: #1c60c7;
            font-weight: 700;
            white-space: nowrap;
        }

        .recent-row {
            display: flex;
            align-items: center;
            gap: 13px;
            border-top: 1px solid #edf1f7;
            padding: 13px 0;
        }

        .recent-row:first-of-type {
            border-top: 0;
        }

        .recent-thumb {
            width: 69px;
            height: 58px;
            flex: none;
            display: grid;
            place-items: center;
            border-radius: 9px;
            background: #e7eff9;
            color: #4975ad;
            overflow: hidden;
        }

        .recent-thumb img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .recent-name {
            font-size: 14px;
            font-weight: 750;
            overflow-wrap: anywhere;
        }

        .recent-meta {
            font-size: 12px;
            color: var(--suave);
            line-height: 1.5;
            margin-top: 3px;
        }

        .recent-status {
            margin-left: auto;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 11px;
            white-space: nowrap;
            background: #dcfce7;
            color: #166534;
            font-weight: 700;
        }

        .empty-recent {
            padding: 25px 4px;
            color: var(--suave);
            font-size: 14px;
            line-height: 1.5;
        }

        .stat-row {
            display: flex;
            gap: 15px;
            align-items: center;
            margin: 15px 0;
        }

        .stat-icon {
            flex: none;
            width: 47px;
            height: 47px;
            display: grid;
            place-items: center;
            border-radius: 13px;
            background: #e8f1ff;
            color: #1861cf;
        }

        .stat-icon.green {
            background: #ddf8e9;
            color: #138957;
        }

        .stat-icon.violet {
            background: #edeaff;
            color: #674fe0;
        }

        .stat-value {
            font-size: 25px;
            font-weight: 850;
            line-height: 1.1;
        }

        .stat-label {
            margin-top: 2px;
            font-size: 13px;
            color: var(--suave);
        }

        .my-summary {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
            padding-top: 17px;
            border-top: 1px solid var(--borde);
        }

        .my-summary a {
            background: #f1f6ff;
            color: #1556ae;
            border-radius: 9px;
            padding: 9px 12px;
            font-size: 12px;
            font-weight: 700;
        }

        .notice {
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 22px;
            background: #dff8e9;
            color: #14633f;
            border: 1px solid #a7e7bf;
        }

        /* NOTIFICACIONES */

        .notification-wrapper {
            position: relative;
        }

        .bell-button {
            position: relative;
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border: 0;
            color: #163d72;
            background: #f2f6fd;
            border-radius: 12px;
        }

        .bell-button:hover {
            background: #e2edff;
        }

        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            min-width: 21px;
            height: 21px;
            padding: 0 5px;
            border-radius: 15px;
            background: #ec4545;
            color: white;
            font-size: 11px;
            font-weight: 800;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .notification-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 52px;
            width: 350px;
            max-width: calc(100vw - 30px);
            color: #1e293b;
            background: white;
            border: 1px solid var(--borde);
            border-radius: 14px;
            z-index: 100;
            overflow: hidden;
            box-shadow: 0 17px 42px rgba(12, 39, 76, .20);
        }

        .notification-menu.open {
            display: block;
        }

        .notification-menu-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 18px;
            border-bottom: 1px solid var(--borde);
        }

        .notification-menu-header h3 {
            margin: 0;
            color: #1958b4;
            font-size: 16px;
        }

        .notification-menu-header span {
            font-size: 12px;
            color: var(--suave);
        }

        .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 15px 18px;
            border-bottom: 1px solid var(--borde);
        }

        .notification-item:hover {
            background: #f6faff;
        }

        .notification-item.unread {
            background: #ecf4ff;
        }

        .notification-item p {
            margin: 0 0 8px;
            font-size: 13px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .notification-item small {
            color: var(--suave);
        }

        .notification-footer {
            display: block;
            text-align: center;
            font-weight: 700;
            padding: 15px;
            font-size: 13px;
            color: #1d5cbc;
            background: #f8fbff;
        }

        .notification-footer:hover {
            background: #e4efff;
        }

        .empty-notifications {
            padding: 30px 15px;
            text-align: center;
            color: var(--suave);
            font-size: 14px;
        }

        .toast-container {
            position: fixed;
            top: 90px;
            right: 20px;
            z-index: 200;
            width: 340px;
            max-width: calc(100vw - 40px);
        }

        .toast {
            background: white;
            border-left: 5px solid #2563eb;
            border-radius: 12px;
            padding: 18px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .18);
            margin-bottom: 10px;
        }

        .toast strong {
            display: block;
            margin-bottom: 8px;
            color: #1d4ed8;
        }

        .toast p {
            margin: 0;
            font-size: 14px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .mobile-overlay {
            display: none;
        }

        /* TABLET */

        @media (max-width: 1080px) {
            .quick-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .bottom-grid {
                grid-template-columns: 1fr;
            }

            .page-content {
                padding: 30px 25px 45px;
            }

            .topbar {
                padding: 14px 24px;
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

            .mobile-menu-btn {
                display: grid;
                place-items: center;
            }

            .mobile-overlay.open {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(6, 22, 45, .52);
                z-index: 35;
            }

            .topbar {
                padding: 12px 15px;
                gap: 10px;
            }

            .topbar-search {
                min-width: 0;
            }

            .topbar-search input {
                height: 40px;
            }

            .header-actions {
                gap: 7px;
            }

            .user-name {
                display: none;
            }

            .avatar {
                width: 33px;
                height: 33px;
            }

            .page-content {
                padding: 26px 15px 35px;
            }

            .hero {
                grid-template-columns: 1fr;
            }

            .hero-graphic {
                display: none;
            }

            .hero-copy {
                padding: 28px 23px;
            }

            .quick-grid {
                grid-template-columns: 1fr;
            }

            .quick-card {
                min-height: 0;
            }

            .search-form {
                flex-direction: column;
            }

            .panel {
                padding: 17px;
            }

            .recent-thumb {
                width: 58px;
                height: 54px;
            }

            .recent-status {
                display: none;
            }

            .notification-menu {
                right: -50px;
            }
        }
    </style>
</head>

<body>

    <!-- ICONOS SVG -->

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

        <symbol id="i-arrow" viewBox="0 0 24 24">
            <path d="M5 12h14m-6-6 6 6-6 6"/>
        </symbol>

        <symbol id="i-out" viewBox="0 0 24 24">
            <path d="M10 4H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h5M15 8l4 4-4 4M19 12H9"/>
        </symbol>

        <symbol id="i-menu" viewBox="0 0 24 24">
            <path d="M4 6h16M4 12h16M4 18h16"/>
        </symbol>

        <symbol id="i-users" viewBox="0 0 24 24">
            <circle cx="9" cy="8" r="4"/>
            <path d="M2 21v-2a7 7 0 0 1 14 0v2M17 5a4 4 0 0 1 0 7M18 15a6 6 0 0 1 4 6"/>
        </symbol>

        <symbol id="i-help" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9"/>
            <path d="M9 9a3 3 0 1 1 5 2.5c-1.5 1-2 1.5-2 3M12 18h.01"/>
        </symbol>
    </svg>

    <!-- MENÚ MÓVIL -->

    <div class="mobile-overlay" id="mobileOverlay"></div>

    <div class="app">

        <!-- BARRA LATERAL -->

        <aside
            class="sidebar"
            id="sidebar"
            aria-label="Navegación principal"
        >

            <a href="{{ route('dashboard') }}" class="brand">
                <span class="brand-symbol">
                    <svg class="icon">
                        <use href="#i-logo"/>
                    </svg>
                </span>

                <span>UniFind</span>
            </a>

            <nav class="nav-links">

                <a
                    class="nav-link active"
                    href="{{ route('dashboard') }}"
                    aria-current="page"
                >
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
                    <svg class="icon"><use href="#i-users"/></svg>
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

                <!-- El perfil se implementará en el siguiente paso -->

                
<a
    class="nav-link"
    href="{{ route('perfil.index') }}"
>
    <svg class="icon">
        <use href="#i-user"/>
    </svg>
    Mi perfil
</a>


            </nav>

            <div class="sidebar-footer">

                <svg
                    class="sidebar-art"
                    viewBox="0 0 240 110"
                    aria-hidden="true"
                >
                    <g
                        stroke="currentColor"
                        fill="none"
                        stroke-width="2"
                    >
                        <path d="M21 95h200M35 92V52l85-40 85 40v40M29 52h181M46 61h148M47 83h148"/>
                        <path d="M64 61v22M88 61v22M112 61v22M136 61v22M160 61v22M184 61v22"/>
                        <path d="M110 83V70h20v13M40 95v-9h160v9"/>
                        <path d="M5 97l18-27 11 27M217 97l8-28 11 28"/>
                    </g>
                </svg>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="logout-form"
                >
                    @csrf

                    <button class="logout" type="submit">
                        <svg class="icon">
                            <use href="#i-out"/>
                        </svg>
                        Cerrar sesión
                    </button>
                </form>

            </div>

        </aside>

        <!-- CONTENIDO -->

        <div class="workspace">

            <!-- BARRA SUPERIOR -->

            <header class="topbar">

                <button
                    class="mobile-menu-btn"
                    type="button"
                    id="menuToggle"
                    aria-label="Abrir menú"
                    aria-expanded="false"
                >
                    <svg class="icon">
                        <use href="#i-menu"/>
                    </svg>
                </button>

                <form
                    class="topbar-search"
                    method="GET"
                    action="{{ route('objetos-encontrados.explorar') }}"
                >
                    <svg class="icon">
                        <use href="#i-search"/>
                    </svg>

                    <input
                        type="search"
                        name="buscar"
                        aria-label="Buscar objetos encontrados"
                        placeholder="Buscar celular, computador, llaves..."
                    >
                </form>

                <div class="header-actions">

                    <!-- CAMPANA DE NOTIFICACIONES -->

                    <div
                        class="notification-wrapper"
                        id="notificationWrapper"
                    >

                        <button
                            type="button"
                            class="bell-button"
                            id="bellButton"
                            aria-label="Abrir notificaciones"
                            aria-expanded="false"
                            aria-controls="notificationMenu"
                        >
                            <svg class="icon">
                                <use href="#i-bell"/>
                            </svg>

                            <span
                                class="notification-count"
                                id="notificationCount"
                            >
                                0
                            </span>
                        </button>

                        <div
                            class="notification-menu"
                            id="notificationMenu"
                        >

                            <div class="notification-menu-header">
                                <h3>Notificaciones</h3>
                                <span id="notificationSummary">
                                    0 nuevas
                                </span>
                            </div>

                            <div
                                class="notification-list"
                                id="notificationList"
                            >
                                <div class="empty-notifications">
                                    Cargando notificaciones...
                                </div>
                            </div>

                            <a
                                href="{{ route('notificaciones.index') }}"
                                class="notification-footer"
                            >
                                Ver todas las notificaciones
                            </a>

                        </div>

                    </div>

                    <!-- USUARIO ACTUAL -->

                    <div
                        class="user-chip"
                        aria-label="Usuario actual"
                    >

                        <span class="avatar">
                            {{ strtoupper(substr(trim($usuarioActual->name), 0, 1)) }}
                        </span>

                        <span class="user-name">
                            {{ $usuarioActual->name }}
                        </span>

                    </div>

                </div>

            </header>

            <!-- AVISOS -->

            <div
                class="toast-container"
                id="toastContainer"
                aria-live="polite"
            ></div>

            <main class="page-content">

                @if (session('success'))
                    <div class="notice" role="status">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- BIENVENIDA -->

                <div class="welcome-top">

                    <div>
                        <h1>
                            ¡Bienvenido a UniFind,
                            {{ $usuarioActual->name }}! 👋
                        </h1>

                        <p>
                            Gestiona objetos perdidos y encontrados
                            dentro de la universidad.
                        </p>
                    </div>

                    <span class="role-tag">
                        <svg class="icon">
                            <use href="#i-logo"/>
                        </svg>

                        {{ $usuarioActual->rol?->nombre ?? 'Usuario' }}
                    </span>

                </div>

                <!-- BANNER -->

                <section
                    class="hero"
                    aria-label="Bienvenida a UniFind"
                >

                    <div class="hero-copy">

                        <div class="hero-eyebrow">
                            Tu comunidad universitaria
                        </div>

                        <h2>
                            ¿Perdiste o encontraste un objeto?
                        </h2>

                        <p>
                            Publícalo en UniFind, revisa los
                            objetos encontrados y ayuda a
                            que cada pertenencia regrese
                            a su dueño.
                        </p>

                    </div>

                    <div
                        class="hero-graphic"
                        aria-hidden="true"
                    >

                        <svg
                            viewBox="0 0 330 178"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >

                            <circle
                                cx="245"
                                cy="44"
                                r="44"
                                fill="white"
                                fill-opacity=".1"
                            />

                            <path
                                d="M44 155H305"
                                stroke="white"
                                stroke-opacity=".42"
                                stroke-width="2"
                            />

                            <path
                                d="M75 154V83L171 39L270 83V154"
                                fill="white"
                                fill-opacity=".17"
                                stroke="white"
                                stroke-opacity=".75"
                                stroke-width="2"
                            />

                            <path
                                d="M62 84H280L171 33L62 84Z"
                                fill="white"
                                fill-opacity=".24"
                                stroke="white"
                                stroke-opacity=".8"
                                stroke-width="2"
                            />

                            <path
                                d="M91 96H254M91 140H254"
                                stroke="white"
                                stroke-opacity=".8"
                                stroke-width="2"
                            />

                            <path
                                d="M108 97V140M131 97V140M212 97V140M236 97V140"
                                stroke="white"
                                stroke-opacity=".65"
                                stroke-width="7"
                            />

                            <path
                                d="M154 154V119C154 104 189 104 189 119V154"
                                stroke="white"
                                stroke-opacity=".75"
                                stroke-width="2"
                            />

                            <circle
                                cx="49"
                                cy="128"
                                r="18"
                                fill="#9AD4FF"
                                fill-opacity=".55"
                            />

                            <path
                                d="M49 146V159"
                                stroke="white"
                                stroke-opacity=".75"
                                stroke-width="3"
                            />

                            <circle
                                cx="299"
                                cy="131"
                                r="16"
                                fill="#B9DEFA"
                                fill-opacity=".5"
                            />

                            <path
                                d="M299 145V159"
                                stroke="white"
                                stroke-opacity=".75"
                                stroke-width="3"
                            />

                        </svg>

                    </div>

                </section>

                <!-- ACCESOS RÁPIDOS -->

                <h2 class="section-title">
                    Accesos rápidos
                </h2>

                <section
                    class="quick-grid"
                    aria-label="Accesos rápidos"
                >

                    <a
                        href="{{ route('objetos-perdidos.create') }}"
                        class="quick-card"
                    >

                        <span class="quick-top">

                            <span class="quick-icon">
                                <svg class="icon">
                                    <use href="#i-plus"/>
                                </svg>
                            </span>

                            <svg class="icon quick-arrow">
                                <use href="#i-arrow"/>
                            </svg>

                        </span>

                        <strong>
                            Reportar objeto perdido
                        </strong>

                        <span class="desc">
                            Registra lo que has extraviado.
                        </span>

                    </a>

                    <a
                        href="{{ route('objetos-encontrados.create') }}"
                        class="quick-card"
                    >

                        <span class="quick-top">

                            <span class="quick-icon violet">
                                <svg class="icon">
                                    <use href="#i-box"/>
                                </svg>
                            </span>

                            <svg class="icon quick-arrow">
                                <use href="#i-arrow"/>
                            </svg>

                        </span>

                        <strong>
                            Reportar objeto encontrado
                        </strong>

                        <span class="desc">
                            Informa sobre un objeto que encontraste.
                        </span>

                    </a>

                    <a
                        href="{{ route('objetos-encontrados.explorar') }}"
                        class="quick-card"
                    >

                        <span class="quick-top">

                            <span class="quick-icon green">
                                <svg class="icon">
                                    <use href="#i-search"/>
                                </svg>
                            </span>

                            <svg class="icon quick-arrow">
                                <use href="#i-arrow"/>
                            </svg>

                        </span>

                        <strong>
                            Explorar objetos
                        </strong>

                        <span class="desc">
                            Busca los objetos encontrados disponibles.
                        </span>

                    </a>

                </section>

                <!-- BUSCADOR PRINCIPAL -->

                <section class="search-panel">

                    <h3>
                        Buscar en la plataforma
                    </h3>

                    <form
                        class="search-form"
                        action="{{ route('objetos-encontrados.explorar') }}"
                        method="GET"
                    >

                        <input
                            type="search"
                            name="buscar"
                            aria-label="Buscar en la plataforma"
                            placeholder="Ej. Mochila, laptop, credencial, llaves..."
                        >

                        <button
                            class="primary-btn"
                            type="submit"
                        >
                            Buscar
                        </button>

                    </form>

                </section>

                <!-- OBJETOS RECIENTES Y ESTADÍSTICAS -->

                <div class="bottom-grid">

                    <!-- OBJETOS ENCONTRADOS RECIENTES -->

                    <section
                        class="panel"
                        aria-labelledby="recientesTitle"
                    >

                        <div class="panel-title">

                            <h3 id="recientesTitle">
                                Últimos objetos encontrados
                            </h3>

                            <a
                                href="{{ route('objetos-encontrados.explorar') }}"
                            >
                                Ver todos →
                            </a>

                        </div>

                        @forelse ($ultimosObjetos as $objeto)

                            <a
                                href="{{ route('objetos-encontrados.show', $objeto->id) }}"
                                class="recent-row"
                            >

                                <span class="recent-thumb">

                                    @if ($objeto->imagen)

                                        <img
                                            src="{{ asset('storage/' . $objeto->imagen) }}"
                                            alt="Imagen de {{ $objeto->titulo }}"
                                            loading="lazy"
                                        >

                                    @else

                                        <svg class="icon">
                                            <use href="#i-box"/>
                                        </svg>

                                    @endif

                                </span>

                                <span style="min-width:0;flex:1">

                                    <span class="recent-name">
                                        {{ $objeto->titulo }}
                                    </span>

                                    <span
                                        class="recent-meta"
                                        style="display:block"
                                    >
                                        {{ $objeto->ubicacion }}

                                        ·

                                        {{ $objeto->created_at?->diffForHumans() ?? 'Reciente' }}
                                    </span>

                                </span>

                                <span class="recent-status">
                                    Disponible
                                </span>

                            </a>

                        @empty

                            <div class="empty-recent">
                                Todavía no hay objetos encontrados
                                disponibles. Cuando alguien publique
                                uno, aparecerá aquí.
                            </div>

                        @endforelse

                    </section>

                    <!-- ESTADÍSTICAS REALES -->

                    <section
                        class="panel"
                        aria-labelledby="statsTitle"
                    >

                        <div class="panel-title">

                            <h3 id="statsTitle">
                                UniFind en números
                            </h3>

                        </div>

                        <div class="stat-row">

                            <span class="stat-icon">
                                <svg class="icon">
                                    <use href="#i-box"/>
                                </svg>
                            </span>

                            <div>

                                <div class="stat-value">
                                    {{ $totalReportados }}
                                </div>

                                <div class="stat-label">
                                    Reportes de objetos registrados
                                </div>

                            </div>

                        </div>

                        <div class="stat-row">

                            <span class="stat-icon green">
                                <svg class="icon">
                                    <use href="#i-check"/>
                                </svg>
                            </span>

                            <div>

                                <div class="stat-value">
                                    {{ $totalRecuperados }}
                                </div>

                                <div class="stat-label">
                                    Objetos encontrados recuperados
                                </div>

                            </div>

                        </div>

                        <div class="stat-row">

                            <span class="stat-icon violet">
                                <svg class="icon">
                                    <use href="#i-users"/>
                                </svg>
                            </span>

                            <div>

                                <div class="stat-value">
                                    {{ $totalUsuarios }}
                                </div>

                                <div class="stat-label">
                                    Usuarios registrados
                                </div>

                            </div>

                        </div>

                        <div class="my-summary">

                            <a href="{{ route('objetos-perdidos.index') }}">
                                Mis pérdidas: {{ $misObjetosPerdidos }}
                            </a>

                            <a href="{{ route('objetos-encontrados.index') }}">
                                Mis hallazgos: {{ $misObjetosEncontrados }}
                            </a>

                            <a href="{{ route('solicitudes.index') }}">
                                Mis solicitudes →
                            </a>

                        </div>

                    </section>

                </div>

            </main>

        </div>

    </div>

    <!-- CONTROL DEL MENÚ EN CELULARES -->

    <script>
        const sidebar = document.getElementById('sidebar');
        const menuToggle = document.getElementById('menuToggle');
        const mobileOverlay = document.getElementById('mobileOverlay');

        function closeMobileMenu() {
            sidebar.classList.remove('open');
            mobileOverlay.classList.remove('open');
            menuToggle.setAttribute('aria-expanded', 'false');
        }

        menuToggle.addEventListener('click', () => {
            const opened = sidebar.classList.toggle('open');

            mobileOverlay.classList.toggle('open', opened);

            menuToggle.setAttribute(
                'aria-expanded',
                String(opened)
            );
        });

        mobileOverlay.addEventListener(
            'click',
            closeMobileMenu
        );

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeMobileMenu();
            }
        });
    </script>

    <!-- SISTEMA ORIGINAL DE NOTIFICACIONES -->

    <script>
        const bellButton = document.getElementById('bellButton');
        const notificationMenu = document.getElementById('notificationMenu');
        const notificationWrapper = document.getElementById('notificationWrapper');
        const notificationCount = document.getElementById('notificationCount');
        const notificationSummary = document.getElementById('notificationSummary');
        const notificationList = document.getElementById('notificationList');
        const toastContainer = document.getElementById('toastContainer');

        const notificationUrl = @json(route('notificaciones.recientes'));
        const marcarLeidasUrl = @json(route('notificaciones.marcar-leidas'));

        let lastNotificationId = null;
        let initialized = false;
        let audioContext = null;
        let soundEnabled = false;

        bellButton.addEventListener('click', async () => {
            const open = notificationMenu.classList.toggle('open');

            bellButton.setAttribute(
                'aria-expanded',
                String(open)
            );

            if (open) {
                try {
                    const response = await fetch(marcarLeidasUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        throw new Error(
                            'No se pudieron marcar las notificaciones.'
                        );
                    }

                    await loadNotifications();

                } catch (error) {
                    console.error(error);
                }
            }
        });

        document.addEventListener('click', (event) => {
            if (!notificationWrapper.contains(event.target)) {
                notificationMenu.classList.remove('open');

                bellButton.setAttribute(
                    'aria-expanded',
                    'false'
                );
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                notificationMenu.classList.remove('open');

                bellButton.setAttribute(
                    'aria-expanded',
                    'false'
                );
            }
        });

        async function enableSound() {
            if (soundEnabled) return;

            try {
                const AudioContextClass =
                    window.AudioContext ||
                    window.webkitAudioContext;

                if (!AudioContextClass) return;

                audioContext = new AudioContextClass();

                await audioContext.resume();

                soundEnabled =
                    audioContext.state === 'running';

            } catch (error) {
                console.warn(
                    'El navegador no permitió activar el sonido.'
                );
            }
        }

        document.addEventListener('click', enableSound);
        document.addEventListener('keydown', enableSound);

        function playNotificationSound() {
            if (!soundEnabled || !audioContext) return;

            try {
                const oscillator = audioContext.createOscillator();
                const gain = audioContext.createGain();

                oscillator.type = 'sine';

                oscillator.frequency.setValueAtTime(
                    880,
                    audioContext.currentTime
                );

                gain.gain.setValueAtTime(
                    0.12,
                    audioContext.currentTime
                );

                gain.gain.exponentialRampToValueAtTime(
                    0.001,
                    audioContext.currentTime + 0.25
                );

                oscillator.connect(gain);
                gain.connect(audioContext.destination);

                oscillator.start();

                oscillator.stop(
                    audioContext.currentTime + 0.25
                );

            } catch (error) {
                console.warn(
                    'No se pudo reproducir el sonido.'
                );
            }
        }

        function showToast(message) {
            const toast = document.createElement('div');

            toast.className = 'toast';

            const title = document.createElement('strong');

            title.textContent = '🔔 Nueva notificación';

            const paragraph = document.createElement('p');

            paragraph.textContent = message;

            toast.appendChild(title);
            toast.appendChild(paragraph);
            toastContainer.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 6000);
        }

        function renderNotifications(notifications, unreadCount) {
            notificationList.replaceChildren();

            notificationSummary.textContent =
                unreadCount +
                (unreadCount === 1 ? ' nueva' : ' nuevas');

            notificationCount.textContent =
                unreadCount > 99
                    ? '99+'
                    : String(unreadCount);

            notificationCount.style.display =
                unreadCount > 0 ? 'flex' : 'none';

            if (notifications.length === 0) {
                const empty = document.createElement('div');

                empty.className = 'empty-notifications';

                empty.textContent =
                    'No tienes notificaciones.';

                notificationList.appendChild(empty);

                return;
            }

            notifications.forEach((notification) => {
                const item = document.createElement('a');

                if (notification.objeto_encontrado_id) {

                    item.href = @json(
                        route(
                            'objetos-encontrados.show',
                            ['objeto' => '__ID__']
                        )
                    ).replace(
                        '__ID__',
                        encodeURIComponent(
                            notification.objeto_encontrado_id
                        )
                    );

                } else {

                    item.href = @json(
                        route('solicitudes.recibidas')
                    );

                }

                item.style.display = 'block';
                item.style.textDecoration = 'none';
                item.style.color = 'inherit';
                item.style.cursor = 'pointer';

                item.className = 'notification-item';

                if (notification.estado === 'no_leido') {
                    item.classList.add('unread');
                }

                const message = document.createElement('p');

                message.textContent = notification.mensaje;

                const date = document.createElement('small');

                date.textContent = new Date(
                    notification.created_at
                ).toLocaleString('es-CO');

                item.appendChild(message);
                item.appendChild(date);

                notificationList.appendChild(item);
            });
        }

        async function loadNotifications() {
            try {
                const response = await fetch(notificationUrl, {
                    headers: {
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    cache: 'no-store'
                });

                if (!response.ok) {
                    throw new Error(
                        'Error al consultar notificaciones'
                    );
                }

                const data = await response.json();

                const notifications = data.notificaciones || [];

                const unreadCount = Number(
                    data.sin_leer || 0
                );

                renderNotifications(
                    notifications,
                    unreadCount
                );

                const newestId =
                    notifications.length > 0
                        ? Number(notifications[0].id)
                        : 0;

                if (
                    initialized &&
                    newestId > lastNotificationId
                ) {

                    const newNotifications = notifications.filter(
                        (notification) =>
                            Number(notification.id) >
                            lastNotificationId
                    );

                    if (newNotifications.length > 0) {
                        playNotificationSound();

                        newNotifications
                            .slice()
                            .reverse()
                            .forEach((notification) => {
                                showToast(notification.mensaje);
                            });
                    }
                }

                lastNotificationId = Math.max(
                    lastNotificationId ?? 0,
                    newestId
                );

                initialized = true;

            } catch (error) {
                console.error(
                    'No se pudieron cargar las notificaciones.',
                    error
                );

                if (!initialized) {
                    notificationList.textContent =
                        'No se pudieron cargar las notificaciones.';
                }
            }
        }

        loadNotifications();

        setInterval(loadNotifications, 10000);
    </script>

</body>
</html>
