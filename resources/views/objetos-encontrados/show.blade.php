
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del objeto encontrado | UniFind</title>

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
            max-width: 850px;
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

        .card {
            background: white;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        }

        .card img {
            display: block;
            width: 100%;
            max-height: 380px;
            object-fit: contain;
            background: #f8fafc;
        }

        .no-image {
            height: 260px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #dbeafe;
            color: #1e40af;
            font-weight: bold;
        }

        .content {
            padding: 25px;
        }

        .content h3 {
            margin-top: 0;
            color: #1d4ed8;
            font-size: 24px;
        }

        .content p {
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .tag {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: bold;
        }

        label {
            display: block;
            margin: 20px 0 8px;
            font-size: 14px;
            font-weight: bold;
            color: #334155;
        }

        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: white;
            color: #1e293b;
        }

        .report-help {
            margin: 0 0 16px;
            padding: 12px;
            background: #eff6ff;
            border-radius: 8px;
            color: #1e40af;
            font-size: 13px;
            line-height: 1.6;
        }

        button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
        }

        button:hover {
            background: #1d4ed8;
        }

        .message {
            color: #64748b;
            font-size: 14px;
        }

        .errors {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <header>
        <h1>UniFind</h1>
    </header>

    <main>

        <a href="{{ route('objetos-encontrados.explorar') }}" class="back">
            ← Volver a objetos encontrados
        </a>

        <h2>Publicación del objeto encontrado</h2>

        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="errors">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <article class="card">

            @if ($objeto->imagen)
                <img
                    src="{{ asset('storage/' . $objeto->imagen) }}"
                    alt="Fotografía del objeto encontrado"
                >
            @else
                <div class="no-image">
                    Sin fotografía
                </div>
            @endif

            <div class="content">

                <h3>{{ $objeto->titulo }}</h3>

                <p>
                    <strong>Categoría:</strong>
                    {{ $objeto->categoria?->nombre ?? 'Sin categoría' }}
                </p>

                <p>
                    <strong>Descripción:</strong>
                    {{ $objeto->descripcion }}
                </p>

                <p>
                    <strong>Lugar:</strong>
                    {{ $objeto->ubicacion }}
                </p>

                <p>
                    <strong>Fecha:</strong>
                    {{ $objeto->fecha_encontrado?->format('d/m/Y') }}
                </p>

                <span class="tag">
                    {{ ucfirst($objeto->estado) }}
                </span>

                @if ($objeto->estado === 'disponible')

                    @if ((int) $objeto->usuario_id !== (int) auth()->id())

                        <form
                            action="{{ route('solicitudes.store', $objeto->id) }}"
                            method="POST"
                        >
                            @csrf

                            <label for="objeto_perdido_id">
                                ¿Ya reportaste este objeto como perdido?
                            </label>

                            <select
                                id="objeto_perdido_id"
                                name="objeto_perdido_id"
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
                                        — {{ $perdido->lugar_perdida }}
                                    </option>
                                @endforeach
                            </select>

                            @if ($objetosPerdidos->isNotEmpty())

                                <p class="report-help">
                                    <strong>Importante:</strong>
                                    Si este objeto corresponde a uno que
                                    ya reportaste como perdido,
                                    selecciónalo en la lista.

                                    Cuando ambas personas confirmen
                                    la entrega y la recepción,
                                    UniFind actualizará automáticamente
                                    el estado del objeto encontrado
                                    y de tu reporte de pérdida.

                                    Si ninguno corresponde,
                                    puedes dejar seleccionada
                                    la primera opción.
                                </p>

                            @else

                                <p class="report-help">
                                    No tienes reportes de pérdida activos.

                                    Puedes solicitar la recuperación
                                    sin vincular un reporte.

                                    Cuando ambas personas confirmen
                                    la recuperación, UniFind actualizará
                                    el estado del objeto encontrado.
                                </p>

                            @endif

                            <button type="submit">
                                Solicitar recuperación
                            </button>

                        </form>

                    @else

                        <p class="message">
                            Este objeto fue publicado por ti.
                        </p>

                    @endif

                @else

                    <p class="message">
                        Este objeto ya fue recuperado y no está disponible
                        para nuevas solicitudes.
                    </p>

                @endif

            </div>

        </article>

    </main>

</body>
</html>
