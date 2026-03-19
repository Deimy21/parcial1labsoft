@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Editar Vivero</h1>
        <a href="{{ route('viveros.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('viveros.update', $vivero) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="finca_id" class="form-label">Finca *</label>
                    <select name="finca_id" id="finca_id" class="form-select @error('finca_id') is-invalid @enderror" required>
                        <option value="">Seleccione una finca</option>
                        @foreach($fincas as $finca)
                            <option value="{{ $finca->id }}" {{ (old('finca_id') ?? $vivero->finca_id) == $finca->id ? 'selected' : '' }}>
                                {{ $finca->numero_catastro }} - {{ $finca->municipio }} ({{ $finca->productor->nombre }} {{ $finca->productor->apellido }})
                            </option>
                        @endforeach
                    </select>
                    @error('finca_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="codigo" class="form-label">Código del Vivero *</label>
                    <input type="text" 
                           class="form-control @error('codigo') is-invalid @enderror" 
                           id="codigo" 
                           name="codigo" 
                           value="{{ old('codigo', $vivero->codigo) }}"
                           required>
                    <small class="text-muted">Código único asignado por el productor</small>
                    @error('codigo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tipo_cultivo" class="form-label">Tipo de Cultivo *</label>
                    <input type="text" 
                           class="form-control @error('tipo_cultivo') is-invalid @enderror" 
                           id="tipo_cultivo" 
                           name="tipo_cultivo" 
                           value="{{ old('tipo_cultivo', $vivero->tipo_cultivo) }}"
                           required>
                    @error('tipo_cultivo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Actualizar Vivero</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection