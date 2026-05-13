<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Labores - {{ $vivero->nombre }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h2 { color: #2c6e49; }
        h4 { color: #555; margin-bottom: 2px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th { background-color: #2c6e49; color: white; padding: 6px; text-align: left; }
        td { border: 1px solid #ccc; padding: 5px; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .labor-box { border-left: 4px solid #2c6e49; padding: 6px 10px; margin-bottom: 14px; background: #f9f9f9; }
        .badge { padding: 2px 6px; border-radius: 3px; color: white; font-size: 10px; }
        .hongo { background: #e6a800; }
        .plaga { background: #c0392b; }
        .fertilizante { background: #27ae60; }
        .footer { text-align: center; font-size: 10px; color: #999; margin-top: 20px; }
    </style>
</head>
<body>
    <h2>🌿 Labores ejecutadas en el Vivero: {{ $vivero->nombre }}</h2>
    <p>
        <strong>Productor:</strong> {{ $vivero->productor->nombre }} {{ $vivero->productor->apellido }} |
        <strong>Documento:</strong> {{ $vivero->productor->documento_identidad }} |
        <strong>Municipio:</strong> {{ $vivero->municipio }}, {{ $vivero->departamento }}
    </p>
    <hr>

    @if($labores->isEmpty())
        <p>No hay labores registradas para este vivero.</p>
    @else
        @foreach($labores as $labor)
        <div class="labor-box">
            <h4>{{ $labor->descripcion }} &nbsp;
                <small style="color:#888;">{{ \Carbon\Carbon::parse($labor->fecha)->format('d/m/Y') }}</small>
            </h4>

            @if($labor->productoControl)
                @php $p = $labor->productoControl; @endphp
                <table>
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Nombre</th>
                            <th>Reg. ICA</th>
                            <th>Frecuencia</th>
                            <th>Valor</th>
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                @if($p->tipo === 'hongo')
                                    <span class="badge hongo">Hongo</span>
                                @elseif($p->tipo === 'plaga')
                                    <span class="badge plaga">Plaga</span>
                                @else
                                    <span class="badge fertilizante">Fertilizante</span>
                                @endif
                            </td>
                            <td>{{ $p->nombre_producto }}</td>
                            <td>{{ $p->registro_ica }}</td>
                            <td>Cada {{ $p->frecuencia_aplicacion }} días</td>
                            <td>${{ number_format($p->valor_producto, 2) }}</td>
                            <td>
                                @if($p->tipo === 'hongo')
                                    Hongo: {{ $p->nombre_hongo }} | Carencia: {{ $p->periodo_carencia }} días
                                @elseif($p->tipo === 'plaga')
                                    Carencia: {{ $p->periodo_carencia }} días
                                @else
                                    Última: {{ $p->fecha_ultima_aplicacion ? \Carbon\Carbon::parse($p->fecha_ultima_aplicacion)->format('d/m/Y') : 'N/A' }}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            @else
                <p style="color:#888; font-size:11px;">Sin producto de control asociado.</p>
            @endif
        </div>
        @endforeach
    @endif

    <div class="footer">Generado el {{ now()->format('d/m/Y H:i') }} — Sistema de Viveros</div>
</body>
</html>