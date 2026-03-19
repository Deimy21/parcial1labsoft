@extends('layouts.app')

@section('title', $productor->nombre.' '.$productor->apellido)
@section('topbar-title', 'Productores')
@section('topbar-subtitle', $productor->nombre.' '.$productor->apellido)

@section('topbar-actions')
    <a href="{{ route('productores.index') }}" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Volver
    </a>
    <a href="{{ route('productores.edit', $productor) }}" class="btn btn-amber">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        Editar
    </a>
    <button onclick="confirmDelete('{{ route('productores.destroy', $productor) }}', '¿Eliminar a {{ $productor->nombre }} {{ $productor->apellido }}? Acción irreversible.')"
        class="btn btn-danger">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3,6 5,6 21,6"/><path d="M19,6l-1,14a2,2,0,01-2,2H8a2,2,0,01-2-2L5,6"/></svg>
        Eliminar
    </button>
@endsection

@section('content')

<div class="breadcrumb">
    <a href="{{ route('productores.index') }}">Productores</a>
    <span>›</span>
    <span>{{ $productor->nombre }} {{ $productor->apellido }}</span>
</div>

<div style="display:grid; grid-template-columns:300px 1fr; gap:24px; align-items:start;">

    {{-- Profile card --}}
    <div style="display:flex; flex-direction:column; gap:16px;">
        <div class="card">
            <div class="card-body" style="text-align:center;">
                <div style="width:72px;height:72px;border-radius:18px;background:var(--green-light);color:var(--green-deep);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.5rem;margin:0 auto 14px;">
                    {{ strtoupper(substr($productor->nombre,0,1).substr($productor->apellido,0,1)) }}
                </div>
                <h2 style="font-size:1.2rem; margin:0 0 4px;">{{ $productor->nombre }} {{ $productor->apellido }}</h2>
                <span class="badge badge-green" style="margin-bottom:16px;">{{ $productor->documento_identidad }}</span>
            </div>
            <div style="border-top:1px solid var(--border);">
                @foreach([
                    ['icon'=>'mail', 'label'=>'Correo', 'val'=>$productor->correo],
                    ['icon'=>'phone', 'label'=>'Teléfono', 'val'=>$productor->telefono],
                ] as $row)
                <div style="display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid var(--border);">
                    <div style="width:34px;height:34px;border-radius:8px;background:var(--green-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        @if($row['icon']==='mail')
                        <svg width="16" height="16" fill="none" stroke="var(--green-deep)" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        @else
                        <svg width="16" height="16" fill="none" stroke="var(--green-deep)" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.12 1.25 2 2 0 012.11 3h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 10.09a16 16 0 006 6l.46-.46a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0120 18h.92z"/></svg>
                        @endif
                    </div>
                    <div>
                        <div style="font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;font-weight:600;">{{ $row['label'] }}</div>
                        <div style="font-size:.875rem;font-weight:500;">{{ $row['val'] }}</div>
                    </div>
                </div>
                @endforeach
                <div style="display:flex;align-items:center;gap:12px;padding:14px 20px;">
                    <div style="width:34px;height:34px;border-radius:8px;background:var(--green-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="16" height="16" fill="none" stroke="var(--green-deep)" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/></svg>
                    </div>
                    <div>
                        <div style="font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Fincas</div>
                        <div style="font-size:.875rem;font-weight:600;color:var(--green-deep);">{{ $productor->fincas->count() }} registrada(s)</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div class="stat-card" style="text-align:center;">
                <div style="font-size:1.8rem;font-weight:700;color:var(--green-deep);font-family:'Playfair Display',serif;">{{ $productor->fincas->count() }}</div>
                <div style="font-size:.75rem;color:var(--text-muted);">Fincas</div>
            </div>
            <div class="stat-card" style="text-align:center;">
                <div style="font-size:1.8rem;font-weight:700;color:var(--amber);font-family:'Playfair Display',serif;">{{ $productor->fincas->sum(fn($f) => $f->viveros->count()) }}</div>
                <div style="font-size:.75rem;color:var(--text-muted);">Viveros</div>
            </div>
        </div>
    </div>

    {{-- Fincas section --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        <div class="card">
            <div class="card-header">
                <div>
                    <h3 style="font-size:1.05rem;margin:0;">Fincas Asociadas</h3>
                    <p style="font-size:.8rem;color:var(--text-muted);margin:2px 0 0;">Propiedades registradas a este productor</p>
                </div>
                <a href="{{ route('fincas.create', ['productor_id' => $productor->id]) }}" class="btn btn-primary btn-sm">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                    Agregar Finca
                </a>
            </div>

            @if($productor->fincas->isEmpty())
                <div style="text-align:center;padding:48px 24px;color:var(--text-muted);">
                    <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 10px;display:block;opacity:.4;"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    <p style="font-size:.875rem;margin:0;">Sin fincas registradas aún</p>
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Nº Catastro</th>
                                <th>Municipio</th>
                                <th>Viveros</th>
                                <th style="text-align:right;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productor->fincas as $finca)
                            <tr>
                                <td>
                                    <span class="badge badge-green">{{ $finca->numero_catastro }}</span>
                                </td>
                                <td style="font-weight:500;">{{ $finca->municipio }}</td>
                                <td>
                                    <span style="font-weight:600;color:var(--green-deep);">{{ $finca->viveros->count() }}</span>
                                    <span style="font-size:.8rem;color:var(--text-muted);"> vivero(s)</span>
                                </td>
                                <td>
                                    <div style="display:flex;gap:6px;justify-content:flex-end;">
                                        <a href="{{ route('fincas.edit', $finca) }}" class="btn btn-amber btn-sm">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            Editar
                                        </a>
                                        <button onclick="confirmDelete('{{ route('fincas.destroy', $finca) }}', '¿Eliminar la finca {{ $finca->numero_catastro }}? Se eliminarán sus viveros.')"
                                            class="btn btn-danger btn-sm">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3,6 5,6 21,6"/><path d="M19,6l-1,14a2,2,0,01-2,2H8a2,2,0,01-2-2L5,6"/></svg>
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Viveros anidados --}}
                            @if($finca->viveros->isNotEmpty())
                            @foreach($finca->viveros as $vivero)
                            <tr style="background:#fafcfb;">
                                <td colspan="4" style="padding:8px 16px 8px 48px;">
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <svg width="14" height="14" fill="none" stroke="var(--text-muted)" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                        <span class="badge badge-amber" style="font-size:.72rem;">{{ $vivero->codigo }}</span>
                                        <span style="font-size:.82rem;color:var(--text-muted);">Cultivo: <strong style="color:var(--text-main);">{{ $vivero->tipo_cultivo }}</strong></span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection