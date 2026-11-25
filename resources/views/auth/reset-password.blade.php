<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña | UniFind</title>
    <style>
        *{box-sizing:border-box}
        body{margin:0;min-height:100vh;display:grid;place-items:center;background:#f2f6fc;color:#142b4b;font-family:Arial,sans-serif;padding:22px}
        main{width:100%;max-width:440px;background:white;padding:32px;border-radius:18px;box-shadow:0 12px 35px #123b7020}
        h1{font-size:24px;color:#102f5b;text-align:center}
        label{display:block;font-size:14px;font-weight:bold;margin:15px 0 7px}
        input{width:100%;padding:13px;border:1px solid #cbd8e9;border-radius:9px}
        button{width:100%;padding:13px;background:#1763d4;color:white;border:0;border-radius:9px;font-weight:bold;margin-top:18px;cursor:pointer}
        a{display:block;text-align:center;margin-top:18px;color:#1763d4}
        .error{background:#fee2e2;color:#991b1b;padding:12px;border-radius:8px}
    </style>
</head>
<body>
<main>
    <h1>Restablecer contraseña</h1>
    @if ($errors->any())
        <div class="error" role="alert">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif
    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" required autocomplete="email">
        <label for="password">Nueva contraseña</label>
        <input type="password" id="password" name="password" required autocomplete="new-password">
        <label for="password_confirmation">Confirmar contraseña</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
        <button type="submit">Cambiar contraseña</button>
    </form>
    <a href="{{ route('login') }}">Volver al inicio</a>
</main>
</body>
</html>
