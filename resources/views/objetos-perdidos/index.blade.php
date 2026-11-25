
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis objetos perdidos | UniFind</title>

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

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #2563eb;
            text-decoration: none;
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
            color: #334155;
            background: #f8fafc;
        }

        .estado {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            padding: 7px 10px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            padding: 40px 15px;
            color: #64748b;
        }

        .foto {
            width: 65px;
            height: 65px;
            object-fit: cover;
            border-radius: 8px;
        }

        .pagination {
            margin-top: 20px;
        }

        .recuperar-btn {
            background: #15803d;
            color: #fff;
            border: 0;
            border-radius: 8px;
            padding: 8px 10px;
            cursor: pointer;
            font-weight: bold;
            white-space: nowrap;
        }
        .aviso {
            background: #dcfce7;
            color: #166534;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 14px;
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
            <h2>Mis objetos perdidos</h2>

            <a href="{{ route('objetos-perdidos.create') }}" class="btn">
                + Reportar objeto
            </a>
        </div>

        @if (session('success'))
            <div class="aviso" role="status">{{ session('success') }}</div>
        @endif

        <div class="table-container">

            @if ($objetos->isEmpty())

                <div class="empty">
                    <h3>Aún no tienes objetos perdidos registrados.</h3>
                    <p>
                        Cuando reportes un objeto, aparecerá aquí.
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
                            <th>Lugar</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($objetos as $objeto)
                            <tr>
                                <td>
                                    @if ($objeto->imagen)
                                        <img
                                            src="{{ asset('storage/' . $objeto->imagen) }}"
                                            alt="Fotografía del objeto"
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

                                <td>{{ $objeto->lugar_perdida }}</td>

                                <td>
                                    {{ $objeto->fecha_perdida?->format('d/m/Y') }}
                                </td>

                                <td>
                                    <span class="estado">
                                        {{ ucfirst($objeto->estado) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($objeto->estado === 'perdido')
                                        <form method="POST"
                                              action="{{ route('objetos-perdidos.recuperado', $objeto->id) }}"
                                              onsubmit="return confirm('¿Confirmas que este objeto ya fue recuperado físicamente? Se retirará de Explorar, pero permanecerá en tu historial.');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="recuperar-btn">
                                                Marcar recuperado
                                            </button>
                                        </form>
                                    @else
                                        <span style="color:#15803d;font-weight:bold;">✓ Recuperado</span>
                                    @endif
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
