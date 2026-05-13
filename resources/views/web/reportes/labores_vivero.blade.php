@extends('layouts.app')

@section('title', 'Labores por Vivero')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">🌿 Labores ejecutadas en un Vivero</h1>

    {{-- Formulario de filtro --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Seleccionar Vivero</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reportes.labores-vivero') }}">
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label for="vivero_id">Vivero</label>
                        <select name="vivero_id" id="vivero_id" class="form-control" required>
                            <option value="">-- Seleccione un vivero --</option>
                            @foreach($viveros as $vivero)
                                <option value="{{ $vivero->id }}"
                                    {{ $viveroSeleccionado && $viveroSeleccionado->id == $vivero->id ? 'selected' : '' }}>
                                    {{ $vivero->nombre }} ({{ $vivero->productor->nombre }} {{ $vivero->productor->apellido }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Consultar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Resultados --}}
    @if($viveroSeleccionado)
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                Resultados para: <strong>{{ $viveroSeleccionado->nombre }}</strong>
            </h6>
            <div>
                <a href="{{ route('reportes.labores-vivero.pdf', $viveroSeleccionado->id) }}"
                   class="btn btn-danger btn-sm" target="_blank">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
                <a href="{{ route('reportes.labores-vivero.excel', $viveroSeleccionado->id) }}"
                   class="btn btn-success btn-sm ml-1">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($labores->isEmpty())
                <p class="text-muted">Este vivero no tiene labores registradas.</p>
            @else
                @foreach($labores as $labor)
                <div class="card mb-3 border-left-info">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-info">
                            {{ $labor->descripcion }}
                            <span class="badge badge-secondary ml-2">{{ \Carbon\Carbon::parse($labor->fecha)->format('d/m/Y') }}</span>
                        </h6>

                        @if($labor->productoControl)
                            @php $producto = $labor->productoControl; @endphp
                            <table class="table table-sm table-bordered mt-2">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Nombre Producto</th>
                                        <th>Registro ICA</th>
                                        <th>Frecuencia</th>
                                        <th>Valor</th>
                                        <th>Detalle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            @if($producto->tipo === 'hongo')
                                                <span class="badge badge-warning">Hongo</span>
                                            @elseif($producto->tipo === 'plaga')
                                                <span class="badge badge-danger">Plaga</span>
                                            @else
                                                <span class="badge badge-success">Fertilizante</span>
                                            @endif
                                        </td>
                                        <td>{{ $producto->nombre_producto }}</td>
                                        <td>{{ $producto->registro_ica }}</td>
                                        <td>Cada {{ $producto->frecuencia_aplicacion }} días</td>
                                        <td>${{ number_format($producto->valor_producto, 2) }}</td>
                                        <td>
                                            @if($producto->tipo === 'hongo')
                                                Hongo: {{ $producto->nombre_hongo }} | Carencia: {{ $producto->periodo_carencia }} días
                                            @elseif($producto->tipo === 'plaga')
                                                Carencia: {{ $producto->periodo_carencia }} días
                                            @else
                                                Última aplic.: {{ $producto->fecha_ultima_aplicacion ? \Carbon\Carbon::parse($producto->fecha_ultima_aplicacion)->format('d/m/Y') : 'N/A' }}
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted small mb-0">Sin producto de control asociado.</p>
                        @endif

                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
    @endif
</div>
@endsection