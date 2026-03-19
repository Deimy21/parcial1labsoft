@extends('layouts.app')

@section('title', 'Productores')
@section('topbar-title', 'Productores')
@section('topbar-subtitle', 'Lista')

@section('topbar-actions')
    <a href="{{ route('productores.create') }}" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        Nuevo Productor
    </a>
@endsection

@section('content')

{{-- Search --}}
<form method="GET" action="{{ route('productores.index') }}" style="margin-bottom:24px;">
    <div style="display:flex; gap:12px; align-items:center;">
        <div class="search-wrap" style="flex:1; max-width:420px;">
            <svg class="search-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input type="text" name="documento" value="{{ request('documento') }}"
                placeholder="Buscar por documento de identidad…"
                class="form-input" style="padding-left:42px;">
        </div>
        <button type="submit" class="btn btn-secondary">Buscar</button>
        @if(request('documento'))
            <a href="{{ route('productores.index') }}" class="btn btn-secondary">Limpiar</a>
        @endif
    </div>
</form>

{{-- Table card --}}
<div class="card">
    <div class="card-header">
        <div>
            <h3 style="font-size:1.1rem; margin:0;">Lista de Productores</h3>
            <p style="font-size:.8rem; color:var(--text-muted); margin:2px 0 0;">
                {{ $productores->total() }} registros encontrados
            </p>
        </div>
    </div>

    @if($productores->isEmpty())
        <div style="text-align:center; padding:60px 24px; color:var(--text-muted);">
            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px; display:block; opacity:.4;"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            <p style="font-size:.9rem; margin:0;">No se encontraron productores</p>
            @if(request('documento'))
                <p style="font-size:.8rem; margin-top:6px;">Prueba con otro documento de identidad</p>
            @endif
        </div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Productor</th>
                    <th>Documento</th>
                    <th>Contacto</th>
                    <th>Fincas</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productores as $p)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div class="avatar">{{ strtoupper(substr($p->nombre,0,1).substr($p->apellido,0,1)) }}</div>
                            <div>
                                <div style="font-weight:600;">{{ $p->nombre }} {{ $p->apellido }}</div>
                                <div style="font-size:.78rem; color:var(--text-muted);">{{ $p->correo }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-green">{{ $p->documento_identidad }}</span>
                    </td>
                    <td style="color:var(--text-muted); font-size:.85rem;">{{ $p->telefono }}</td>
                    <td>
                        <span style="font-weight:600; color:var(--green-deep);">{{ $p->fincas_count }}</span>
                        <span style="font-size:.8rem; color:var(--text-muted);"> finca(s)</span>
                    </td>
                    <td>
                        <div style="display:flex; gap:6px; justify-content:flex-end;">
                            <a href="{{ route('productores.show', $p) }}" class="btn btn-secondary btn-sm">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                Ver
                            </a>
                            <a href="{{ route('productores.edit', $p) }}" class="btn btn-amber btn-sm">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Editar
                            </a>
                            <button onclick="confirmDelete('{{ route('productores.destroy', $p) }}', '¿Eliminar a {{ $p->nombre }} {{ $p->apellido }}? Se eliminarán también sus fincas y viveros.')"
                                class="btn btn-danger btn-sm">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3,6 5,6 21,6"/><path d="M19,6l-1,14a2,2,0,01-2,2H8a2,2,0,01-2-2L5,6"/></svg>
                                Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($productores->hasPages())
    <div style="padding:16px 20px; border-top:1px solid var(--border);">
        {{ $productores->withQueryString()->links() }}
    </div>
    @endif
    @endif
</div>

@endsection