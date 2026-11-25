<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña | UniFind</title>
    <style>
        *{box-sizing:border-box}
        body{margin:0;min-height:100vh;display:grid;place-items:center;padding:22px;background:#f2f6fc;color:#142b4b;font-family:Arial,sans-serif}
        main{width:100%;max-width:440px;background:white;border-radius:18px;padding:32px;box-shadow:0 12px 35px #123b7020}
        h1{text-align:center;color:#102f5b;font-size:25px;margin-top:0}
        p{color:#62728a;line-height:1.6;font-size:14px}
        label{display:block;margin:20px 0 8px;font-weight:bold;font-size:14px}
        input{width:100%;padding:13px;border:1px solid #cbd8e9;border-radius:9px}
        button{width:100%;padding:13px;background:#1763d4;color:white;border:0;border-radius:9px;font-weight:bold;margin-top:18px;cursor:pointer}
        a{display:block;text-align:center;margin-top:20px;color:#1763d4}
        .error{padding:10px;background:#fee2e2;color:#991b1b;border-radius:8px;margin-bottom:12px}
        .success{padding:10px;background:#dcfce7;color:#166534;border-radius:8px;margin-bottom:12px}
    </style>
</head>
<body>
<main>
    <h1>UniFind · Recuperar contraseña</h1>
    <p>Escribe el correo de tu cuenta para solicitar un enlace de recuperación.</p>
    @if (session('status')) <div class="success">{{ session('status') }}</div> @endif
    @if ($errors->any())
        <div class="error" role="alert">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <label for="email">Correo electrónico</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
        <button type="submit">Solicitar enlace</button>
    </form>
    <a href="{{ route('login') }}">Volver a iniciar sesión</a>
</main>
</body>
</html>
