
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis objetos encontrados | UniFind</title>

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
            max-width: 1100px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #2563eb;
            text-decoration: none;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .top h2 {
            margin: 0;
            color: #1d4ed8;
        }

        .btn {
            display: inline-block;
            padding: 12px 16px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }

        .table-container {
            background: white;
            border-radius: 14px;
            padding: 20px;
            overflow-x: auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background: #f8fafc;
            color: #334155;
        }

        .estado {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            padding: 7px 10px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: bold;
        }

        .foto {
            width: 65px;
            height: 65px;
            object-fit: cover;
            border-radius: 8px;
        }

        .empty {
            text-align: center;
            padding: 40px 15px;
            color: #64748b;
        }

        .pagination {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <header>
        <h1>UniFind</h1>
    </header>

    <main>

        <a href="{{ route('dashboard') }}" class="back">
            ← Volver al panel
        </a>

        <div class="top">
            <h2>Mis objetos encontrados</h2>

            <a href="{{ route('objetos-encontrados.create') }}" class="btn">
                + Reportar objeto encontrado
            </a>
        </div>

        <div class="table-container">

            @if ($objetos->isEmpty())

                <div class="empty">
                    <h3>Aún no has reportado objetos encontrados.</h3>
                    <p>
                        Cuando registres un objeto encontrado,
                        aparecerá aquí.
                    </p>
                </div>

            @else

                <table>
                    <thead>
                        <tr>
                            <th>Fotografía</th>
                            <th>Objeto</th>
                            <th>Categoría</th>
                            <th>Descripción</th>
                            <th>Ubicación</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($objetos as $objeto)
                            <tr>
                                <td>
                                    @if ($objeto->imagen)
                                        <img
                                            src="{{ asset('storage/' . $objeto->imagen) }}"
                                            alt="Fotografía del objeto encontrado"
                                            class="foto"
                                        >
                                    @else
                                        Sin foto
                                    @endif
                                </td>

                                <td>{{ $objeto->titulo }}</td>

                                <td>
                                    {{ $objeto->categoria?->nombre ?? 'Sin categoría' }}
                                </td>

                                <td>{{ $objeto->descripcion }}</td>

                                <td>{{ $objeto->ubicacion }}</td>

                                <td>
                                    {{ $objeto->fecha_encontrado?->format('d/m/Y') }}
                                </td>

                                <td>
                                    <span class="estado">
                                        {{ ucfirst($objeto->estado) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination">
                    {{ $objetos->links() }}
                </div>

            @endif

        </div>

    </main>

</body>
</html>
