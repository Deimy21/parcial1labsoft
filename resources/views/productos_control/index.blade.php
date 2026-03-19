@extends('layouts.app')
@section('title', 'Productos de Control')
@section('topbar-title', 'Productos de Control')
@section('topbar-subtitle', 'Lista')
@section('topbar-actions')
    <a href="{{ route('productos-control.create') }}" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        Nuevo Producto
    </a>
@endsection

@section('content')

{{-- Filter tabs --}}
<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
    @foreach([null=>'Todos','hongo'=>'Hongos','plaga'=>'Plagas','fertilizante'=>'Fertilizantes'] as $val=>$label)
    <a href="{{ route('productos-control.index', $val ? ['tipo'=>$val] : []) }}"
       style="padding:8px 18px;border-radius:24px;font-size:.85rem;font-weight:600;text-decoration:none;transition:all .15s;
              {{ request('tipo')===$val || (is_null($val) && !request('tipo'))
                 ? 'background:var(--green-deep);color:#fff;'
                 : 'background:var(--white);color:var(--text-muted);border:1px solid var(--border);' }}">
        {{ $label }}
        @php
            $count = match($val) {
                null => \App\Models\ProductoControl::count(),
                default => \App\Models\ProductoControl::where('tipo',$val)->count()
            };
        @endphp
        <span style="margin-left:4px;opacity:.7;">({{ $count }})</span>
    </a>
    @endforeach
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 style="font-size:1.1rem;margin:0;">
                @if(request('tipo')) {{ ucfirst(request('tipo')).'s' }} @else Todos los Productos @endif
            </h3>
            <p style="font-size:.8rem;color:var(--text-muted);margin:2px 0 0;">{{ $productos->total() }} productos registrados</p>
        </div>
    </div>

    @if($productos->isEmpty())
        <div style="text-align:center;padding:60px 24px;color:var(--text-muted);">
            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;opacity:.4;"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <p style="font-size:.9rem;margin:0;">Sin productos registrados</p>
        </div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Nombre del Producto</th>
                    <th>Registro ICA</th>
                    <th>Frec. Aplicación</th>
                    <th>Valor</th>
                    <th>Específico</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $pc)
                @php
                    $badgeClass = match($pc->tipo) {'hongo'=>'badge-hongo','plaga'=>'badge-plaga','fertilizante'=>'badge-fertilizante',default=>'badge-green'};
                    $icon = match($pc->tipo) {'hongo'=>'🍄','plaga'=>'🐛','fertilizante'=>'🌱',default=>'⚗️'};
                @endphp
                <tr>
                    <td>
                        <span class="badge {{ $badgeClass }}">{{ $icon }} {{ ucfirst($pc->tipo) }}</span>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:.9rem;">{{ $pc->nombre_producto }}</div>
                    </td>
                    <td>
                        <code style="background:var(--bg);padding:2px 8px;border-radius:6px;font-size:.8rem;">{{ $pc->registro_ica }}</code>
                    </td>
                    <td style="text-align:center;">
                        <span style="font-weight:600;">{{ $pc->frecuencia_aplicacion }}</span>
                        <span style="font-size:.78rem;color:var(--text-muted);"> días</span>
                    </td>
                    <td>
                        <span style="font-weight:600;color:var(--green-deep);">${{ number_format($pc->valor_producto, 0, ',', '.') }}</span>
                    </td>
                    <td style="font-size:.82rem;color:var(--text-muted);">
                        @if($pc->tipo === 'hongo')
                            {{ $pc->nombre_hongo }} · {{ $pc->periodo_carencia }}d carencia
                        @elseif($pc->tipo === 'plaga')
                            {{ $pc->periodo_carencia }} días carencia
                        @elseif($pc->tipo === 'fertilizante')
                            Última: {{ $pc->fecha_ultima_aplicacion ? \Carbon\Carbon::parse($pc->fecha_ultima_aplicacion)->format('d/m/Y') : '—' }}
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:flex-end;">
                            <a href="{{ route('productos-control.edit', $pc) }}" class="btn btn-amber btn-sm">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Editar
                            </a>
                            <button onclick="confirmDelete('{{ route('productos-control.destroy', $pc) }}', '¿Eliminar {{ $pc->nombre_producto }}?')"
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
    @if($productos->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);">
        {{ $productos->withQueryString()->links() }}
    </div>
    @endif
    @endif
</div>
@endsection