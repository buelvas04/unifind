
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | UniFind</title>

    <style>
        :root {
            --azul: #155fc7;
            --azul-oscuro: #102f5b;
            --texto: #142b4b;
            --gris: #61738c;
            --borde: #d6e2f1;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(
                circle at 10% 15%,
                #e7f0ff,
                transparent 44%
            ), #f4f7fc;
            color: var(--texto);
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        a {
            color: var(--azul);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        button, input {
            font: inherit;
        }

        .shell {
            width: 100%;
            max-width: 1240px;
            min-height: 100vh;
            margin: 0 auto;
            padding: 24px;
            display: grid;
            grid-template-columns: 255px minmax(0, 1fr);
            align-items: center;
            gap: 27px;
        }

        /* INFORMACIÓN LATERAL */

        .information {
            min-width: 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 18px;
            color: var(--azul-oscuro);
            font-size: 34px;
            font-weight: 800;
            letter-spacing: -.8px;
        }

        .brand-icon {
            width: 46px;
            height: 46px;
            color: var(--azul-oscuro);
            flex: none;
        }

        .information h1 {
            font-size: 20px;
            line-height: 1.4;
            font-weight: 650;
            margin: 0 0 12px;
        }

        .information > p {
            font-size: 13px;
            line-height: 1.65;
            margin: 0 0 22px;
            color: #405779;
        }

        .benefits {
            display: grid;
            gap: 17px;
        }

        .benefit {
            display: flex;
            align-items: flex-start;
            gap: 11px;
        }

        .benefit-icon {
            width: 39px;
            height: 39px;
            border-radius: 11px;
            background: #e5efff;
            display: grid;
            place-items: center;
            color: #1558b7;
            font-size: 19px;
            flex: none;
        }

        .benefit strong {
            display: block;
            font-size: 13px;
            margin: 1px 0 3px;
        }

        .benefit small {
            display: block;
            color: #657993;
            font-size: 12px;
            line-height: 1.4;
        }

        /* TARJETA CENTRAL */

        .login-card {
            min-width: 0;
            width: 100%;
            max-width: 940px;
            height: 510px;
            display: grid;
            grid-template-columns:
                minmax(0, 1fr)
                minmax(0, 1fr);
            overflow: hidden;
            border: 1px solid #dce6f3;
            border-radius: 19px;
            background: #fff;
            box-shadow: 0 16px 45px rgba(36, 77, 129, .13);
        }

        /* FOTO DEL CAMPUS */

        .visual {
            min-width: 0;
            height: 100%;
            background: #ddecf9;
        }

        .visual img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        /* FORMULARIO */

        .form-side {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            justify-content: center;
            min-width: 0;
            padding: 26px 31px;
        }

        .form-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 23px;
            font-weight: 800;
            color: var(--azul-oscuro);
            margin: 0 0 12px;
        }

        .form-logo svg {
            width: 28px;
            height: 28px;
        }

        .form-side h2 {
            margin: 0 0 6px;
            text-align: center;
            font-size: 25px;
            color: #112949;
        }

        .subtitle {
            color: var(--gris);
            font-size: 12px;
            text-align: center;
            line-height: 1.55;
            margin: 0 0 22px;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #314460;
            margin-bottom: 7px;
        }

        .field {
            margin-bottom: 13px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap input {
            display: block;
            width: 100%;
            height: 43px;
            border: 1px solid #cbd8e9;
            border-radius: 9px;
            background: #fbfdff;
            padding: 0 13px;
            color: #183350;
            font-size: 13px;
            outline: none;
        }

        .input-wrap input:focus {
            border-color: var(--azul);
            box-shadow: 0 0 0 3px #1866d216;
        }

        .password-wrap input {
            padding-right: 65px;
        }

        .eye-btn {
            position: absolute;
            top: 50%;
            right: 8px;
            transform: translateY(-50%);
            border: 0;
            border-radius: 7px;
            background: transparent;
            color: #5f7490;
            font-size: 12px;
            padding: 8px;
            cursor: pointer;
        }

        .eye-btn:hover {
            background: #ecf3fd;
        }

        .forgot {
            text-align: right;
            font-size: 12px;
            font-weight: 650;
            margin: 2px 0 18px;
        }

        .submit {
            width: 100%;
            height: 44px;
            border: 0;
            border-radius: 9px;
            color: white;
            font-size: 14px;
            font-weight: 700;
            background: #1763d4;
            cursor: pointer;
        }

        .submit:hover {
            background: #104fae;
        }

        .register {
            margin: 18px 0 0;
            text-align: center;
            font-size: 12px;
            color: #60748e;
        }

        .register a {
            font-weight: 700;
        }

        /* MENSAJES DE LARAVEL */

        .error, .status {
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 12px;
            margin-bottom: 13px;
            line-height: 1.45;
        }

        .error {
            background: #fff0ef;
            border: 1px solid #f4c0bc;
            color: #9d2f28;
        }

        .status {
            background: #e5f8eb;
            border: 1px solid #a8ddbb;
            color: #176039;
        }

        /* AJUSTE PARA PANTALLAS DE PC MÁS BAJAS */

        @media (max-height: 710px) and (min-width: 921px) {
            .login-card {
                height: 480px;
            }

            .form-side {
                padding: 19px 26px;
            }

            .subtitle {
                margin-bottom: 16px;
            }

            .form-logo {
                margin-bottom: 9px;
            }
        }

        /* TABLET */

        @media (max-width: 920px) {
            .shell {
                grid-template-columns: 1fr;
                max-width: 730px;
                padding: 22px;
                gap: 17px;
            }

            .information {
                display: none;
            }

            .login-card {
                height: 510px;
            }
        }

        /* CELULAR */

        @media (max-width: 620px) {
            .shell {
                padding: 12px;
                align-items: start;
            }

            .login-card {
                height: auto;
                grid-template-columns: 1fr;
            }

            .visual {
                height: 180px;
            }

            .visual img {
                object-position: center 58%;
            }

            .form-side {
                padding: 22px 21px 27px;
            }

            .form-side h2 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

    <main class="shell">

        <!-- PRESENTACIÓN DE UNIFIND -->

        <aside class="information" aria-label="Acerca de UniFind">

            <div class="brand">

                <svg
                    class="brand-icon"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >
                    <path d="m2 9 10-5 10 5-10 5L2 9Z"/>
                    <path d="M6 11v5c4 3 8 3 12 0v-5M22 9v6"/>
                </svg>

                <span>UniFind</span>

            </div>

            <h1>
                Plataforma universitaria de objetos
                perdidos y encontrados
            </h1>

            <p>
                Conecta a estudiantes y profesores para
                reportar, buscar y recuperar objetos dentro
                del campus.
            </p>

            <div class="benefits">

                <div class="benefit">
                    <span class="benefit-icon">♧</span>
                    <div>
                        <strong>Para la comunidad</strong>
                        <small>Estudiantes y profesores.</small>
                    </div>
                </div>

                <div class="benefit">
                    <span class="benefit-icon">✓</span>
                    <div>
                        <strong>Gestión confiable</strong>
                        <small>Reporta, solicita y recupera.</small>
                    </div>
                </div>

                <div class="benefit">
                    <span class="benefit-icon">⌂</span>
                    <div>
                        <strong>Entorno universitario</strong>
                        <small>Diseñado para nuestra universidad.</small>
                    </div>
                </div>

            </div>

        </aside>

        <!-- TARJETA PRINCIPAL -->

        <section
            class="login-card"
            aria-label="Iniciar sesión en UniFind"
        >

            <!-- IMAGEN DE LA UNIVERSIDAD -->

            <div class="visual">

                <img
                    src="{{ asset('imagenes/unifind-campus.png') }}"
                    alt="Campus universitario, mochila y elementos académicos de UniFind"
                >

            </div>

            <!-- FORMULARIO DE ACCESO -->

            <div class="form-side">

                <div class="form-logo">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="m2 9 10-5 10 5-10 5L2 9Z"/>
                        <path d="M6 11v5c4 3 8 3 12 0v-5M22 9v6"/>
                    </svg>

                    UniFind

                </div>

                <h2>Iniciar sesión</h2>

                <p class="subtitle">
                    Accede a tu cuenta de la comunidad universitaria.
                </p>

                @if ($errors->any())
                    <div class="error" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="status" role="status">
                        {{ session('status') }}
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('login.store') }}"
                >
                    @csrf

                    <div class="field">

                        <label for="email">
                            Correo electrónico
                        </label>

                        <div class="input-wrap">

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Ingresa tu correo"
                                autocomplete="username"
                                required
                                autofocus
                            >

                        </div>

                    </div>

                    <div class="field">

                        <label for="password">
                            Contraseña
                        </label>

                        <div class="input-wrap password-wrap">

                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="Ingresa tu contraseña"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                type="button"
                                class="eye-btn"
                                id="showPassword"
                                aria-label="Mostrar contraseña"
                                aria-pressed="false"
                            >
                                Ver
                            </button>

                        </div>

                    </div>

                    <div class="forgot">

                        <a href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>

                    </div>

                    <button type="submit" class="submit">
                        Iniciar sesión
                    </button>

                </form>

                <p class="register">

                    ¿No tienes una cuenta?

                    <a href="{{ route('register') }}">
                        Regístrate aquí
                    </a>

                </p>

            </div>

        </section>

    </main>

    <!-- MOSTRAR / OCULTAR CONTRASEÑA -->

    <script>
        const showPassword =
            document.getElementById('showPassword');

        const passwordInput =
            document.getElementById('password');

        showPassword.addEventListener('click', () => {

            const visible =
                passwordInput.type === 'password';

            passwordInput.type =
                visible ? 'text' : 'password';

            showPassword.textContent =
                visible ? 'Ocultar' : 'Ver';

            showPassword.setAttribute(
                'aria-label',
                visible
                    ? 'Ocultar contraseña'
                    : 'Mostrar contraseña'
            );

            showPassword.setAttribute(
                'aria-pressed',
                String(visible)
            );

        });
    </script>

</body>
</html>
