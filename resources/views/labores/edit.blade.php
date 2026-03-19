@extends('layouts.app')
@section('title', 'Editar Labor')
@section('topbar-title', 'Labores')
@section('topbar-subtitle', 'Editar')
@section('topbar-actions')
    <a href="{{ route('labores.index') }}" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Volver
    </a>
@endsection

@section('content')
<div class="breadcrumb">
    <a href="{{ route('labores.index') }}">Labores</a>
    <span>›</span><span>Editar</span>
</div>

<div style="max-width:600px;">
    <div class="card">
        <div class="card-header">
            <h3 style="font-size:1.05rem;margin:0;">Editar Labor</h3>
        </div>
        <form method="POST" action="{{ route('labores.update', $labor) }}">
            @csrf @method('PUT')
            <div class="card-body" style="display:flex;flex-direction:column;gap:18px;">

                <div>
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha" value="{{ old('fecha', $labor->fecha) }}" class="form-input {{ $errors->has('fecha') ? 'error' : '' }}">
                    @error('fecha')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label">Vivero</label>
                    <select name="vivero_id" class="form-input form-select {{ $errors->has('vivero_id') ? 'error' : '' }}">
                        <option value="">— Seleccionar vivero —</option>
                        @foreach($viveros as $v)
                            <option value="{{ $v->id }}" {{ old('vivero_id', $labor->vivero_id) == $v->id ? 'selected' : '' }}>
                                {{ $v->codigo }} — {{ $v->tipo_cultivo }} ({{ $v->finca->municipio ?? '' }})
                            </option>
                        @endforeach
                    </select>
                    @error('vivero_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label">Producto de Control</label>
                    <select name="producto_control_id" class="form-input form-select {{ $errors->has('producto_control_id') ? 'error' : '' }}">
                        <option value="">— Seleccionar producto —</option>
                        @foreach($productosControl as $pc)
                            <option value="{{ $pc->id }}" {{ old('producto_control_id', $labor->producto_control_id) == $pc->id ? 'selected' : '' }}>
                                [{{ ucfirst($pc->tipo) }}] {{ $pc->nombre_producto }}
                            </option>
                        @endforeach
                    </select>
                    @error('producto_control_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" rows="3" class="form-input {{ $errors->has('descripcion') ? 'error' : '' }}">{{ old('descripcion', $labor->descripcion) }}</textarea>
                    @error('descripcion')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display:flex;gap:10px;padding-top:4px;">
                    <a href="{{ route('labores.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar Labor</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection