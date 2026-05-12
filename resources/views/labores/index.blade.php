@extends('layouts.app')
@section('title', 'Labores')
@section('topbar-title', 'Labores')
@section('topbar-subtitle', 'Lista')

@section('topbar-actions')
    @if(auth()->user()->rol == 'administrador')
    <a href="{{ route('labores.create') }}" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M12 5v14M5 12h14"/>
        </svg>
        Nueva Labor
    </a>
    @endif
@endsection

@section('content')
<div class="card">

    <div class="card-header">
        <div>
            <h3 style="font-size:1.1rem;margin:0;">Registro de Labores</h3>
            <p style="font-size:.8rem;color:var(--text-muted);margin:2px 0 0;">
                {{ $labores->total() }} labores registradas
            </p>
        </div>
    </div>

    @if($labores->isEmpty())
        <div style="text-align:center;padding:60px 24px;color:var(--text-muted);">
            <p style="margin:0;">No hay labores registradas</p>
        </div>
    @else

    <div class="table-wrap">
        <table>

            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Vivero</th>
                    <th>Municipio</th>
                    <th>Descripción</th>
                    <th>Producto</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach($labores as $labor)

                @php
                    $tipo = $labor->productoControl->tipo ?? 'N/A';

                    $badgeClass = match($tipo) {
                        'hongo' => 'badge-hongo',
                        'plaga' => 'badge-plaga',
                        'fertilizante' => 'badge-fertilizante',
                        default => 'badge-green'
                    };
                @endphp

                <tr>

                    {{-- FECHA --}}
                    <td>
                        <div style="font-weight:600;font-size:.9rem;">
                            {{ \Carbon\Carbon::parse($labor->fecha)->format('d/m/Y') }}
                        </div>
                        <div style="font-size:.75rem;color:var(--text-muted);">
                            {{ \Carbon\Carbon::parse($labor->fecha)->diffForHumans() }}
                        </div>
                    </td>

                    {{-- VIVERO --}}
                    <td>
                        <span class="badge badge-amber">
                            {{ $labor->vivero->codigo ?? '—' }}
                        </span>
                        <div style="font-size:.78rem;color:var(--text-muted);margin-top:2px;">
                            {{ $labor->vivero->nombre ?? '' }}
                        </div>
                    </td>

                    {{-- MUNICIPIO (CORREGIDO) --}}
                    <td style="font-size:.85rem;">
                        {{ $labor->vivero->municipio ?? '—' }}
                    </td>

                    {{-- DESCRIPCIÓN --}}
                    <td style="max-width:200px;">
                        <div style="font-size:.85rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                             title="{{ $labor->descripcion }}">
                            {{ $labor->descripcion }}
                        </div>
                    </td>

                    {{-- PRODUCTO --}}
                    <td>
                        <span class="badge {{ $badgeClass }}">
                            {{ ucfirst($tipo) }}
                        </span>
                        <div style="font-size:.78rem;color:var(--text-muted);margin-top:2px;">
                            {{ $labor->productoControl->nombre_producto ?? '—' }}
                        </div>
                    </td>

                    {{-- ACCIONES --}}
                    <td>
                        <div style="display:flex;gap:6px;justify-content:flex-end;">

                            @if(auth()->user()->rol == 'administrador')

                                <a href="{{ route('labores.edit', $labor) }}" class="btn btn-amber btn-sm">
                                    Editar
                                </a>

                                <button onclick="confirmDelete('{{ route('labores.destroy', $labor) }}', '¿Eliminar esta labor?')"
                                        class="btn btn-danger btn-sm">
                                    Eliminar
                                </button>

                            @endif

                        </div>
                    </td>

                </tr>

                @endforeach
            </tbody>

        </table>
    </div>

    @if($labores->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);">
        {{ $labores->links() }}
    </div>
    @endif

    @endif
</div>
@endsection