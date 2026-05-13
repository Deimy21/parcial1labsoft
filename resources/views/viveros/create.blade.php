@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Nuevo Vivero</h2>
        <a href="{{ route('viveros.index') }}" class="btn btn-secondary">
            Volver
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('viveros.store') }}" method="POST">
                @csrf

                <div class="row">

                    {{-- PRODUCTOR --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Productor</label>
                        <select name="productor_id" class="form-control" required>
                            <option value="">Seleccione un productor</option>
                            @foreach($productores as $productor)
                                <option value="{{ $productor->id }}">
                                    {{ $productor->nombre }} {{ $productor->apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- CÓDIGO --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Código del Vivero</label>
                        <input type="text" name="codigo" class="form-control"
                               placeholder="Ej: VIV-001"
                               value="{{ old('codigo') }}" required>
                    </div>

                    {{-- NOMBRE --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre del Vivero</label>
                        <input type="text" name="nombre" class="form-control"
                               placeholder="Ej: Vivero Central"
                               value="{{ old('nombre') }}" required>
                    </div>

                    {{-- DEPARTAMENTO --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Departamento</label>
                        <input type="text" name="departamento" class="form-control"
                               placeholder="Ej: Risaralda"
                               value="{{ old('departamento') }}" required>
                    </div>

                    {{-- MUNICIPIO --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Municipio</label>
                        <input type="text" name="municipio" class="form-control"
                               placeholder="Ej: Santa Rosa"
                               value="{{ old('municipio') }}" required>
                    </div>

                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-light">
                        Limpiar
                    </button>

                    <button type="submit" class="btn btn-success">
                        Guardar Vivero
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection