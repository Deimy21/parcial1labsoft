<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Viveros - {{ $productor->nombre }} {{ $productor->apellido }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h2 { color: #1a6b3c; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background-color: #1a6b3c; color: white; padding: 7px; text-align: left; }
        td { border: 1px solid #ccc; padding: 6px; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .footer { text-align: center; font-size: 10px; color: #999; margin-top: 24px; }
    </style>
</head>
<body>
    <h2>🏡 Viveros del Productor</h2>
    <p>
        <strong>Nombre:</strong> {{ $productor->nombre }} {{ $productor->apellido }} |
        <strong>Documento:</strong> {{ $productor->documento_identidad }}
    </p>
    <hr>

    @if($viveros->isEmpty())
        <p>Este productor no tiene viveros registrados.</p>
    @else
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Departamento</th>
                <th>Municipio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($viveros as $i => $vivero)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $vivero->codigo }}</td>
                <td>{{ $vivero->nombre }}</td>
                <td>{{ $vivero->departamento }}</td>
                <td>{{ $vivero->municipio }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p><strong>Total de viveros:</strong> {{ $viveros->count() }}</p>
    @endif

    <div class="footer">Generado el {{ now()->format('d/m/Y H:i') }} — Sistema de Viveros</div>
</body>
</html>