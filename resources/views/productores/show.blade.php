@extends('layouts.app')

@section('title', $productor->nombre.' '.$productor->apellido)
@section('topbar-title', 'Productores')
@section('topbar-subtitle', $productor->nombre.' '.$productor->apellido)

@section('topbar-actions')
    <a href="{{ route('productores.index') }}" class="btn btn-secondary">
        Volver
    </a>
    <a href="{{ route('productores.edit', $productor) }}" class="btn btn-amber">
        Editar
    </a>
    <button onclick="confirmDelete('{{ route('productores.destroy', $productor) }}', '¿Eliminar este productor?')"
        class="btn btn-danger">
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

    {{-- PROFILE --}}
    <div class="card">
        <div class="card-body" style="text-align:center;">
            <div style="width:72px;height:72px;border-radius:18px;background:var(--green-light);color:var(--green-deep);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.5rem;margin:0 auto 14px;">
                {{ strtoupper(substr($productor->nombre,0,1).substr($productor->apellido,0,1)) }}
            </div>

            <h2 style="font-size:1.2rem;">
                {{ $productor->nombre }} {{ $productor->apellido }}
            </h2>

            <span class="badge badge-green">
                {{ $productor->documento_identidad }}
            </span>
        </div>

        <div style="border-top:1px solid var(--border);">

            <div style="padding:14px 20px;">
                <strong>Correo:</strong><br>
                {{ $productor->correo }}
            </div>

            <div style="padding:14px 20px;">
                <strong>Teléfono:</strong><br>
                {{ $productor->telefono }}
            </div>

        </div>
    </div>

    {{-- STATS (SIN FINCAS) --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">

        <div class="stat-card" style="text-align:center;">
            <div style="font-size:1.8rem;font-weight:700;">
                0
            </div>
            <div style="font-size:.75rem;">Viveros</div>
        </div>

    </div>

</div>

@endsection