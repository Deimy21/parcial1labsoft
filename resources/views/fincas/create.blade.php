@extends('layouts.app')
@section('title', 'Nueva Finca')
@section('topbar-title', 'Fincas')
@section('topbar-subtitle', 'Nueva')
@section('topbar-actions')
    <a href="{{ route('productores.show', $productor) }}" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Volver
    </a>
@endsection

@section('content')
<div class="breadcrumb">
    <a href="{{ route('productores.index') }}">Productores</a>
    <span>›</span>
    <a href="{{ route('productores.show', $productor) }}">{{ $productor->nombre }} {{ $productor->apellido }}</a>
    <span>›</span><span>Nueva Finca</span>
</div>

<div style="max-width:500px;">
    <div class="card">
        <div class="card-header">
            <div>
                <h3 style="font-size:1.05rem;margin:0;">Agregar Finca</h3>
                <p style="font-size:.8rem;color:var(--text-muted);margin:2px 0 0;">Asociada a: <strong>{{ $productor->nombre }} {{ $productor->apellido }}</strong></p>
            </div>
        </div>
        <form method="POST" action="{{ route('fincas.store') }}">
            @csrf
            <input type="hidden" name="productor_id" value="{{ $productor->id }}">
            <div class="card-body" style="display:flex;flex-direction:column;gap:18px;">

                <div>
                    <label class="form-label">Número de Catastro</label>
                    <input type="text" name="numero_catastro" value="{{ old('numero_catastro') }}" class="form-input {{ $errors->has('numero_catastro') ? 'error' : '' }}" placeholder="Ej: 001-12345">
                    @error('numero_catastro')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label">Municipio</label>
                    <input type="text" name="municipio" value="{{ old('municipio') }}" class="form-input {{ $errors->has('municipio') ? 'error' : '' }}" placeholder="Municipio de ubicación">
                    @error('municipio')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display:flex;gap:10px;padding-top:4px;">
                    <a href="{{ route('productores.show', $productor) }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                        Guardar Finca
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection