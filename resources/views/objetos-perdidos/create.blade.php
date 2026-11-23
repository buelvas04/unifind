
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportar objeto perdido | UniFind</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            background: #f1f5f9;
            font-family: Arial, sans-serif;
            color: #1e293b;
        }

        .container {
            max-width: 650px;
            margin: 35px auto;
            padding: 35px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.07);
        }

        h1 {
            color: #1d4ed8;
            margin-top: 0;
        }

        .subtitle {
            color: #64748b;
            margin-bottom: 28px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font: inherit;
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        input:focus, select:focus, textarea:focus {
            outline: 2px solid #2563eb;
        }

        button {
            width: 100%;
            padding: 13px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #2563eb;
            text-decoration: none;
        }

        .error {
            padding: 12px;
            margin-bottom: 20px;
            background: #fee2e2;
            color: #991b1b;
            border-radius: 8px;
        }

        .help {
            display: block;
            margin: -12px 0 20px;
            color: #64748b;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <main class="container">

        <a href="{{ route('dashboard') }}" class="back">
            ← Volver al panel
        </a>

        <h1>Reportar objeto perdido</h1>

        <p class="subtitle">
            Completa la información para ayudar a encontrar tu objeto.
        </p>

        @if ($errors->any())
            <div class="error" role="alert">
                <strong>Revisa los siguientes datos:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('objetos-perdidos.store') }}"
            enctype="multipart/form-data"
        >
            @csrf

            <label for="titulo">Nombre del objeto</label>
            <input
                type="text"
                id="titulo"
                name="titulo"
                value="{{ old('titulo') }}"
                placeholder="Ejemplo: Calculadora científica"
                maxlength="255"
                required
            >

            <label for="categoria_id">Categoría</label>
            <select id="categoria_id" name="categoria_id" required>
                <option value="">Selecciona una categoría</option>

                @foreach ($categorias as $categoria)
                    <option
                        value="{{ $categoria->id }}"
                        @selected(old('categoria_id') == $categoria->id)
                    >
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>

            <label for="descripcion">Descripción</label>
            <textarea
                id="descripcion"
                name="descripcion"
                maxlength="2000"
                placeholder="Describe el color, marca y características del objeto."
                required
            >{{ old('descripcion') }}</textarea>

            <label for="lugar_perdida">Lugar donde se perdió</label>
            <input
                type="text"
                id="lugar_perdida"
                name="lugar_perdida"
                value="{{ old('lugar_perdida') }}"
                placeholder="Ejemplo: Biblioteca universitaria"
                maxlength="255"
                required
            >

            <label for="fecha_perdida">Fecha de pérdida</label>
            <input
                type="date"
                id="fecha_perdida"
                name="fecha_perdida"
                value="{{ old('fecha_perdida') }}"
                max="{{ now()->toDateString() }}"
                required
            >

            <label for="imagen">Fotografía del objeto (opcional)</label>
            <input
                type="file"
                id="imagen"
                name="imagen"
                accept=".jpg,.jpeg,.png,.webp"
            >
            <small class="help">
                Formatos permitidos: JPG, PNG o WEBP. Tamaño máximo: 2 MB.
            </small>

            <button type="submit">
                Registrar objeto perdido
            </button>
        </form>

    </main>

</body>
</html>
