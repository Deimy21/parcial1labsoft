@extends('layouts.app')

@section('title', 'Fincas')
@section('topbar-title', 'Fincas')
@section('topbar-subtitle', 'Lista')

@section('content')

<div class="card">
    <div class="card-header">
        <h3>Lista de Fincas</h3>
        <p>{{ $fincas->total() }} registros</p>
    </div>

    @if($fincas->isEmpty())
        <p style="padding:20px;">No hay fincas registradas</p>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Finca</th>
                    <th>Municipio</th>
                    <th>Viveros</th>
                    <th>Productor</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fincas as $f)
                <tr>
                    <td>{{ $f->numero_catastro }}</td>
                    <td>{{ $f->municipio }}</td>
                    <td>
                        {{ $f->viveros->count() }}
                    </td>
                    <td>
                        {{ $f->productor->nombre }} {{ $f->productor->apellido }}
                    </td>
                    <td style="text-align:right;">
                        @if(auth()->user()->rol == 'administrador')
                            <a href="{{ route('fincas.edit', $f) }}" class="btn btn-amber btn-sm">Editar</a>
                            <button onclick="confirmDelete('{{ route('fincas.destroy', $f) }}', '¿Eliminar la finca {{ $f->numero_catastro }}? Se eliminarán sus viveros.')"
                                class="btn btn-danger btn-sm">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3,6 5,6 21,6"/><path d="M19,6l-1,14a2,2,0,01-2,2H8a2,2,0,01-2-2L5,6"/></svg>
                                Eliminar
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $fincas->links() }}
    @endif
</div>

@endsection