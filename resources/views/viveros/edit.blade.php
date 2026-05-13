@extends('layouts.app')

@section('content')
<div class="container" style="max-width:700px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Editar Vivero</h2>

        <a href="{{ route('viveros.index') }}" class="btn btn-secondary">
            Volver
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('viveros.update', $vivero) }}">
                @csrf
                @method('PUT')

                {{-- CÓDIGO --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Código del Vivero</label>
                    <input type="text"
                           name="codigo"
                           class="form-control border border-dark"
                           style="background:#fff; color:#000; padding:10px;"
                           value="{{ $vivero->codigo }}"
                           required>
                </div>

                {{-- NOMBRE --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre del Vivero</label>
                    <input type="text"
                           name="nombre"
                           class="form-control border border-dark"
                           style="background:#fff; color:#000; padding:10px;"
                           value="{{ $vivero->nombre }}"
                           required>
                </div>

                {{-- DEPARTAMENTO --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Departamento</label>
                    <input type="text"
                           name="departamento"
                           class="form-control border border-dark"
                           style="background:#fff; color:#000; padding:10px;"
                           value="{{ $vivero->departamento }}"
                           required>
                </div>

                {{-- MUNICIPIO --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Municipio</label>
                    <input type="text"
                           name="municipio"
                           class="form-control border border-dark"
                           style="background:#fff; color:#000; padding:10px;"
                           value="{{ $vivero->municipio }}"
                           required>
                </div>

                {{-- BOTONES --}}
                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="{{ route('viveros.index') }}" class="btn btn-light border">
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Guardar Cambios
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>
@endsection