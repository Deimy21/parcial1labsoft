@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Viveros</h1>
        <a href="{{ route('viveros.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Vivero
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Tipo de Cultivo</th>
                        <th>Finca (Catastro)</th>
                        <th>Productor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($viveros as $vivero)
                    <tr>
                        <td>{{ $vivero->codigo }}</td>
                        <td>{{ $vivero->tipo_cultivo }}</td>
                        <td>{{ $vivero->finca->numero_catastro ?? 'N/A' }}</td>
                        <td>{{ $vivero->finca->productor->nombre ?? 'N/A' }} {{ $vivero->finca->productor->apellido ?? '' }}</td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('viveros.show', $vivero) }}" class="btn btn-sm btn-info" style="display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <a href="{{ route('viveros.edit', $vivero) }}" class="btn btn-sm btn-warning" style="display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                
                                <!-- Botón de eliminar con modal -->
                                <button type="button" class="btn btn-sm btn-danger" style="display: inline-flex; align-items: center; gap: 4px;" 
                                        onclick="confirmDelete('{{ route('viveros.destroy', $vivero) }}', '¿Eliminar este vivero {{ $vivero->codigo }}?')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="d-flex justify-content-end">
                {{ $viveros->links() }}
            </div>
        </div>
    </div>
</div>
@endsection