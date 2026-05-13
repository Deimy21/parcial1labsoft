@extends('layouts.app')

@section('title', 'Viveros por Productor')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">🏡 Viveros de un Productor</h1>

    {{-- Formulario de filtro --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">Seleccionar Productor</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reportes.viveros-productor') }}">
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label for="productor_id">Productor</label>
                        <select name="productor_id" id="productor_id" class="form-control" required>
                            <option value="">-- Seleccione un productor --</option>
                            @foreach($productores as $productor)
                                <option value="{{ $productor->id }}"
                                    {{ $productorSeleccionado && $productorSeleccionado->id == $productor->id ? 'selected' : '' }}>
                                    {{ $productor->nombre }} {{ $productor->apellido }} — {{ $productor->documento_identidad }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mt-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-search"></i> Consultar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Resultados --}}
    @if($productorSeleccionado)
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">
                Viveros de: <strong>{{ $productorSeleccionado->nombre }} {{ $productorSeleccionado->apellido }}</strong>
            </h6>
            <div>
                <a href="{{ route('reportes.viveros-productor.pdf', $productorSeleccionado->id) }}"
                   class="btn btn-danger btn-sm" target="_blank">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
                <a href="{{ route('reportes.viveros-productor.excel', $productorSeleccionado->id) }}"
                   class="btn btn-success btn-sm ml-1">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($viveros->isEmpty())
                <p class="text-muted">Este productor no tiene viveros registrados.</p>
            @else
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Departamento</th>
                        <th>Municipio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($viveros as $vivero)
                    <tr>
                        <td>{{ $vivero->codigo }}</td>
                        <td>{{ $vivero->nombre }}</td>
                        <td>{{ $vivero->departamento }}</td>
                        <td>{{ $vivero->municipio }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection