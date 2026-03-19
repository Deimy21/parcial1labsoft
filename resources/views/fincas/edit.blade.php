@extends('layouts.app')
@section('title', 'Editar Finca')
@section('topbar-title', 'Fincas')
@section('topbar-subtitle', 'Editar')
@section('topbar-actions')
    <a href="{{ route('productores.show', $finca->productor) }}" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Volver
    </a>
@endsection

@section('content')
<div class="breadcrumb">
    <a href="{{ route('productores.index') }}">Productores</a>
    <span>›</span>
    <a href="{{ route('productores.show', $finca->productor) }}">{{ $finca->productor->nombre }} {{ $finca->productor->apellido }}</a>
    <span>›</span><span>Editar Finca</span>
</div>

<div style="max-width:500px;">
    <div class="card">
        <div class="card-header">
            <h3 style="font-size:1.05rem;margin:0;">Editar Finca</h3>
        </div>
        <form method="POST" action="{{ route('fincas.update', $finca) }}">
            @csrf @method('PUT')
            <div class="card-body" style="display:flex;flex-direction:column;gap:18px;">

                <div>
                    <label class="form-label">Número de Catastro</label>
                    <input type="text" name="numero_catastro" value="{{ old('numero_catastro', $finca->numero_catastro) }}" class="form-input {{ $errors->has('numero_catastro') ? 'error' : '' }}">
                    @error('numero_catastro')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label">Municipio</label>
                    <input type="text" name="municipio" value="{{ old('municipio', $finca->municipio) }}" class="form-input {{ $errors->has('municipio') ? 'error' : '' }}">
                    @error('municipio')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display:flex;gap:10px;padding-top:4px;">
                    <a href="{{ route('productores.show', $finca->productor) }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar Finca</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection