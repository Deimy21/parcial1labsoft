@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Detalle del Vivero</h1>
        <div>
            <a href="{{ route('viveros.edit', $vivero) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('viveros.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h5>Información del Vivero</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Código:</th>
                            <td>{{ $vivero->codigo }}</td>
                        </tr>
                        <tr>
                            <th>Tipo de Cultivo:</th>
                            <td>{{ $vivero->tipo_cultivo }}</td>
                        </tr>
                        <tr>
                            <th>Finca:</th>
                            <td>{{ $vivero->finca->numero_catastro ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Municipio:</th>
                            <td>{{ $vivero->finca->municipio ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Productor:</th>
                            <td>
                                @if($vivero->finca && $vivero->finca->productor)
                                    {{ $vivero->finca->productor->nombre }} {{ $vivero->finca->productor->apellido }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Fecha de Registro:</th>
                            <td>{{ $vivero->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Labores Realizadas</h5>
                    <a href="{{ route('labores.create', ['vivero_id' => $vivero->id]) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Nueva Labor
                    </a>
                </div>
                <div class="card-body">
                    @if($vivero->labores->count() > 0)
                        <div class="list-group">
                            @foreach($vivero->labores as $labor)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">{{ $labor->descripcion }}</h6>
                                        <small>{{ $labor->fecha->format('d/m/Y') }}</small>
                                    </div>
                                    @if($labor->productoControl)
                                        <small class="text-muted">
                                            Producto: {{ $labor->productoControl->nombre }}
                                        </small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No hay labores registradas para este vivero.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection