@extends('layouts.app')
@section('title', 'Editar Productor')
@section('topbar-title', 'Productores')
@section('topbar-subtitle', 'Editar')
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
    <span>›</span><span>Editar</span>
</div>

<div style="max-width:640px;">
    <div class="card">
        <div class="card-header">
            <h3 style="font-size:1.05rem;margin:0;">Editar Productor</h3>
        </div>
        <form method="POST" action="{{ route('productores.update', $productor) }}">
            @csrf @method('PUT')
            <div class="card-body" style="display:flex;flex-direction:column;gap:18px;">

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $productor->nombre) }}" class="form-input {{ $errors->has('nombre') ? 'error' : '' }}">
                        @error('nombre')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="form-label">Apellido</label>
                        <input type="text" name="apellido" value="{{ old('apellido', $productor->apellido) }}" class="form-input {{ $errors->has('apellido') ? 'error' : '' }}">
                        @error('apellido')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <label class="form-label">Documento de Identidad</label>
                    <input type="text" name="documento_identidad" value="{{ old('documento_identidad', $productor->documento_identidad) }}" class="form-input {{ $errors->has('documento_identidad') ? 'error' : '' }}">
                    @error('documento_identidad')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="correo" value="{{ old('correo', $productor->correo) }}" class="form-input {{ $errors->has('correo') ? 'error' : '' }}">
                    @error('correo')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $productor->telefono) }}" class="form-input {{ $errors->has('telefono') ? 'error' : '' }}">
                    @error('telefono')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display:flex;gap:10px;padding-top:4px;">
                    <a href="{{ route('productores.show', $productor) }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v14a2 2 0 01-2 2z"/><polyline points="17,21 17,13 7,13 7,21"/></svg>
                        Actualizar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection