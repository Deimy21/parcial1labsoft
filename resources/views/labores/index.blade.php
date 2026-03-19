@extends('layouts.app')
@section('title', 'Labores')
@section('topbar-title', 'Labores')
@section('topbar-subtitle', 'Lista')
@section('topbar-actions')
    <a href="{{ route('labores.create') }}" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        Nueva Labor
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h3 style="font-size:1.1rem;margin:0;">Registro de Labores</h3>
            <p style="font-size:.8rem;color:var(--text-muted);margin:2px 0 0;">{{ $labores->total() }} labores registradas</p>
        </div>
    </div>

    @if($labores->isEmpty())
        <div style="text-align:center;padding:60px 24px;color:var(--text-muted);">
            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;opacity:.4;"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
            <p style="font-size:.9rem;margin:0;">No hay labores registradas</p>
        </div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Vivero</th>
                    <th>Finca / Municipio</th>
                    <th>Descripción</th>
                    <th>Producto Control</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($labores as $labor)
                @php
                    $tipo = $labor->productoControl->tipo ?? 'N/A';
                    $badgeClass = match($tipo) { 'hongo'=>'badge-hongo','plaga'=>'badge-plaga','fertilizante'=>'badge-fertilizante', default=>'badge-green' };
                @endphp
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:.9rem;">{{ \Carbon\Carbon::parse($labor->fecha)->format('d/m/Y') }}</div>
                        <div style="font-size:.75rem;color:var(--text-muted);">{{ \Carbon\Carbon::parse($labor->fecha)->diffForHumans() }}</div>
                    </td>
                    <td>
                        <span class="badge badge-amber">{{ $labor->vivero->codigo ?? '—' }}</span>
                        <div style="font-size:.78rem;color:var(--text-muted);margin-top:2px;">{{ $labor->vivero->tipo_cultivo ?? '' }}</div>
                    </td>
                    <td style="font-size:.85rem;">{{ $labor->vivero->finca->municipio ?? '—' }}</td>
                    <td style="max-width:200px;">
                        <div style="font-size:.85rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $labor->descripcion }}">
                            {{ $labor->descripcion }}
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($tipo) }}</span>
                        <div style="font-size:.78rem;color:var(--text-muted);margin-top:2px;">{{ $labor->productoControl->nombre_producto ?? '—' }}</div>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:flex-end;">
                            <a href="{{ route('labores.edit', $labor) }}" class="btn btn-amber btn-sm">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Editar
                            </a>
                            <button onclick="confirmDelete('{{ route('labores.destroy', $labor) }}', '¿Eliminar esta labor del {{ \Carbon\Carbon::parse($labor->fecha)->format('d/m/Y') }}?')"
                                class="btn btn-danger btn-sm">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3,6 5,6 21,6"/><path d="M19,6l-1,14a2,2,0,01-2,2H8a2,2,0,01-2-2L5,6"/></svg>
                                Eliminar
                            </button>
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