@extends('layouts.app')

@section('content')
<div class="container">

    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="mb-0">Detalle del Vivero</h1>
            <small class="text-muted">Información general</small>
        </div>

        {{-- BOTONES SUPERIORES --}}
        <div style="display:flex; gap:10px;">

            {{-- EDITAR --}}
            <a href="{{ route('viveros.edit', $vivero) }}"
               class="btn btn-sm"
               style="
                    background:#0d6efd;
                    color:white;
                    font-weight:600;
                    border-radius:8px;
                    padding:8px 14px;
               ">
                Editar
            </a>

            {{-- VOLVER --}}
            <a href="{{ route('viveros.index') }}"
               class="btn btn-sm"
               style="
                    background:#6c757d;
                    color:white;
                    font-weight:600;
                    border-radius:8px;
                    padding:8px 14px;
               ">
                Volver
            </a>

        </div>

    </div>

    {{-- TARJETA DE INFORMACIÓN --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="text-muted">Código</label>
                    <div class="fw-bold">{{ $vivero->codigo }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-muted">Nombre</label>
                    <div class="fw-bold">{{ $vivero->nombre }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-muted">Departamento</label>
                    <div>{{ $vivero->departamento }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-muted">Municipio</label>
                    <div>{{ $vivero->municipio }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-muted">Productor</label>
                    <div>
                        {{ $vivero->productor->nombre ?? '' }}
                        {{ $vivero->productor->apellido ?? '' }}
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="text-muted">Fecha creación</label>
                    <div>{{ $vivero->created_at }}</div>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection