@extends('layouts.app')

@section('content')
<div class="container">

    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="mb-0">Viveros</h1>
            <small class="text-muted">
                {{ $viveros->total() }} registros
            </small>
        </div>

        @if(auth()->check() && auth()->user()->rol === 'administrador')

            {{-- BOTÓN NUEVO VIVERO (VERDE) --}}
            <a href="{{ route('viveros.create') }}"
            class="btn"
            style="
                    background-color:#198754;
                    color:white;
                    font-weight:600;
                    padding:10px 18px;
                    border-radius:10px;
                    box-shadow:0 2px 6px rgba(0,0,0,0.15);
            ">
                + Nuevo Vivero
        </a>
        @endif

    </div>

    {{-- TABLA --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Departamento</th>
                            <th>Municipio</th>
                            <th>Productor</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($viveros as $vivero)
                        <tr>

                            <td>
                                <span class="badge bg-primary">
                                    {{ $vivero->codigo }}
                                </span>
                            </td>

                            <td>{{ $vivero->nombre }}</td>
                            <td>{{ $vivero->departamento }}</td>
                            <td>{{ $vivero->municipio }}</td>

                            <td>
                                {{ $vivero->productor->nombre ?? '' }}
                                {{ $vivero->productor->apellido ?? '' }}
                            </td>

                            {{-- ACCIONES --}}
                            <td class="text-end">

                                <div style="display:flex;justify-content:flex-end;gap:10px;">

                                    {{-- VER (AZUL) --}}
                                    <a href="{{ route('viveros.show', $vivero) }}"
                                       style="
                                            color:#0d6efd;
                                            font-weight:600;
                                            text-decoration:none;
                                            padding:6px 10px;
                                            border-radius:6px;
                                            border:1px solid #0d6efd;
                                       ">
                                        Ver
                                    </a>

                                    {{-- EDITAR (AZUL) --}}
                                    <a href="{{ route('viveros.edit', $vivero) }}"
                                       style="
                                            color:#0d6efd;
                                            font-weight:600;
                                            text-decoration:none;
                                            padding:6px 10px;
                                            border-radius:6px;
                                            border:1px solid #0d6efd;
                                       ">
                                        Editar
                                    </a>

                                    {{-- ELIMINAR (ROJO PARA DIFERENCIAR) --}}
                                    <form action="{{ route('viveros.destroy', $vivero) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Eliminar este vivero?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                style="
                                                    background:#dc3545;
                                                    color:white;
                                                    border:none;
                                                    padding:6px 10px;
                                                    border-radius:6px;
                                                    font-weight:600;
                                                ">
                                            Eliminar
                                        </button>
                                    </form>

                                </div>

                            </td>

                        </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    {{-- PAGINACIÓN --}}
    <div class="mt-3">
        {{ $viveros->links() }}
    </div>

</div>
@endsection