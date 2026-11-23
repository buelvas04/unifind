
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta | UniFind</title>

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
            background:
                radial-gradient(
                    circle at 10% 15%,
                    #e7f0ff,
                    transparent 44%
                ),
                #f4f7fc;
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

        button,
        input {
            font: inherit;
        }

        /* DISTRIBUCIÓN GENERAL */

        .shell {
            width: 100%;
            max-width: 1240px;
            min-height: 100vh;
            margin: 0 auto;
            padding: 20px 24px;
            display: grid;
            grid-template-columns: 255px minmax(0, 1fr);
            align-items: center;
            gap: 27px;
        }

        /* INFORMACIÓN DE UNIFIND */

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

        /* TARJETA PRINCIPAL */

        .register-card {
            min-width: 0;
            width: 100%;
            max-width: 940px;
            min-height: 520px;
            display: grid;
            grid-template-columns:
                minmax(0, 1fr)
                minmax(0, 1fr);
            overflow: hidden;
            border: 1px solid #dce6f3;
            border-radius: 19px;
            background: white;
            box-shadow:
                0 16px 45px rgba(36, 77, 129, .13);
        }

        /* IMAGEN UNIVERSITARIA */

        .visual {
            min-width: 0;
            background: #ddecf9;
            overflow: hidden;
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
            justify-content: center;
            min-width: 0;
            padding: 22px 30px;
        }

        .form-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 22px;
            font-weight: 800;
            color: var(--azul-oscuro);
            margin-bottom: 9px;
        }

        .form-logo svg {
            width: 27px;
            height: 27px;
        }

        .form-side h2 {
            margin: 0 0 5px;
            text-align: center;
            font-size: 24px;
            color: #112949;
        }

        .subtitle {
            color: var(--gris);
            font-size: 12px;
            text-align: center;
            line-height: 1.5;
            margin: 0 0 17px;
        }

        .field {
            margin-bottom: 12px;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #314460;
            margin-bottom: 6px;
        }

        .field input {
            display: block;
            width: 100%;
            height: 41px;
            border: 1px solid #cbd8e9;
            border-radius: 9px;
            background: #fbfdff;
            padding: 0 13px;
            color: #183350;
            font-size: 13px;
            outline: none;
        }

        .field input:focus {
            border-color: var(--azul);
            box-shadow: 0 0 0 3px #1866d216;
        }

        .password-wrap {
            position: relative;
        }

        .password-wrap input {
            padding-right: 65px;
        }

        .eye-btn {
            position: absolute;
            top: 50%;
            right: 7px;
            transform: translateY(-50%);
            border: none;
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

        .submit {
            width: 100%;
            height: 43px;
            margin-top: 6px;
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

        .login-link {
            margin: 16px 0 0;
            text-align: center;
            font-size: 12px;
            color: #60748e;
        }

        .login-link a {
            font-weight: 700;
        }

        /* ERRORES DE LARAVEL */

        .error {
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 12px;
            margin-bottom: 13px;
            line-height: 1.5;
            background: #fff0ef;
            border: 1px solid #f4c0bc;
            color: #9d2f28;
        }

        .error ul {
            margin: 6px 0 0;
            padding-left: 18px;
        }

        /* PANTALLAS DE COMPUTADOR PEQUEÑAS */

        @media (max-height: 700px) and (min-width: 921px) {
            .shell {
                padding-top: 12px;
                padding-bottom: 12px;
            }

            .register-card {
                min-height: 0;
            }

            .form-side {
                padding: 16px 26px;
            }

            .field {
                margin-bottom: 9px;
            }

            .subtitle {
                margin-bottom: 12px;
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

            .register-card {
                min-height: 520px;
            }
        }

        /* CELULAR */

        @media (max-width: 620px) {
            .shell {
                padding: 12px;
                align-items: start;
            }

            .register-card {
                min-height: 0;
                grid-template-columns: 1fr;
            }

            .visual {
                height: 170px;
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

    <!-- INFORMACIÓN DE UNIFIND -->

    <aside
        class="information"
        aria-label="Acerca de UniFind"
    >

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
            reportar, buscar y recuperar objetos
            dentro del campus.
        </p>

        <div class="benefits">

            <div class="benefit">

                <span class="benefit-icon">
                    ♧
                </span>

                <div>
                    <strong>
                        Comunidad universitaria
                    </strong>
                    <small>
                        Estudiantes y profesores.
                    </small>
                </div>

            </div>

            <div class="benefit">

                <span class="benefit-icon">
                    ✓
                </span>

                <div>
                    <strong>
                        Publicaciones organizadas
                    </strong>
                    <small>
                        Reporta y consulta objetos.
                    </small>
                </div>

            </div>

            <div class="benefit">

                <span class="benefit-icon">
                    ⌂
                </span>

                <div>
                    <strong>
                        Recuperación de objetos
                    </strong>
                    <small>
                        Solicita y coordina entregas.
                    </small>
                </div>

            </div>

        </div>

    </aside>

    <!-- TARJETA PRINCIPAL -->

    <section
        class="register-card"
        aria-label="Crear cuenta en UniFind"
    >

        <!-- FOTOGRAFÍA DEL CAMPUS -->

        <div class="visual">

            <img
                src="{{ asset('imagenes/unifind-campus.png') }}"
                alt="Campus universitario con mochila y elementos académicos"
            >

        </div>

        <!-- FORMULARIO DE REGISTRO -->

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

            <h2>Crear cuenta</h2>

            <p class="subtitle">
                Únete a nuestra comunidad universitaria.
            </p>

            <!-- ERRORES DE REGISTRO -->

            @if ($errors->any())

                <div class="error" role="alert">

                    <strong>
                        Revisa los siguientes datos:
                    </strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            @endif

            <!-- FORMULARIO ORIGINAL DE LARAVEL -->

            <form
                method="POST"
                action="{{ route('register.store') }}"
            >
                @csrf

                <div class="field">

                    <label for="name">
                        Nombre completo
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Escribe tu nombre completo"
                        autocomplete="name"
                        required
                        autofocus
                    >

                </div>

                <div class="field">

                    <label for="email">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Ingresa tu correo"
                        autocomplete="email"
                        required
                    >

                </div>

                <div class="field">

                    <label for="password">
                        Contraseña
                    </label>

                    <div class="password-wrap">

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Crea una contraseña"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="eye-btn"
                            data-toggle="password"
                            aria-label="Mostrar contraseña"
                            aria-pressed="false"
                        >
                            Ver
                        </button>

                    </div>

                </div>

                <div class="field">

                    <label for="password_confirmation">
                        Confirmar contraseña
                    </label>

                    <div class="password-wrap">

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Repite tu contraseña"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="eye-btn"
                            data-toggle="password_confirmation"
                            aria-label="Mostrar contraseña"
                            aria-pressed="false"
                        >
                            Ver
                        </button>

                    </div>

                </div>

                <button
                    type="submit"
                    class="submit"
                >
                    Crear cuenta
                </button>

            </form>

            <p class="login-link">

                ¿Ya tienes una cuenta?

                <a href="{{ route('login') }}">
                    Inicia sesión
                </a>

            </p>

        </div>

    </section>

</main>

<!-- MOSTRAR Y OCULTAR CONTRASEÑAS -->

<script>
    document.querySelectorAll('[data-toggle]').forEach(
        function (boton) {

            boton.addEventListener('click', function () {

                const input = document.getElementById(
                    boton.dataset.toggle
                );

                if (!input) {
                    return;
                }

                const visible = input.type === 'password';

                input.type = visible ? 'text' : 'password';

                boton.textContent = visible ? 'Ocultar' : 'Ver';

                boton.setAttribute(
                    'aria-label',
                    visible
                        ? 'Ocultar contraseña'
                        : 'Mostrar contraseña'
                );

                boton.setAttribute(
                    'aria-pressed',
                    String(visible)
                );

            });

        }
    );
</script>

</body>
</html>
