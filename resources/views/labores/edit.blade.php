@extends('layouts.app')

@section('title', 'Editar Labor')
@section('topbar-title', 'Labores')
@section('topbar-subtitle', 'Editar')

@section('topbar-actions')
    <a href="{{ route('labores.index') }}" class="btn btn-secondary">
        Volver
    </a>
@endsection

@section('content')

<div class="breadcrumb">
    <a href="{{ route('labores.index') }}">Labores</a>
    <span>›</span>
    <span>Editar</span>
</div>

<div style="max-width:650px; margin:auto;">

    <div class="card" style="border-radius:14px; overflow:hidden; box-shadow:0 6px 18px rgba(0,0,0,0.06);">

        <div class="card-header" style="background:var(--green-light); padding:16px;">
            <h3 style="margin:0; font-size:1.1rem; color:var(--green-deep);">
                Editar Labor
            </h3>
        </div>

        <form method="POST" action="{{ route('labores.update', $labor) }}">
            @csrf
            @method('PUT')

            <div class="card-body" style="display:flex; flex-direction:column; gap:16px; padding:20px;">

                {{-- FECHA --}}
                <div>
                    <label class="form-label">Fecha</label>
                    <input type="date"
                           name="fecha"
                           value="{{ old('fecha', $labor->fecha) }}"
                           class="form-input">
                    @error('fecha')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- VIVERO --}}
                <div>
                    <label class="form-label">Vivero</label>
                    <select name="vivero_id" class="form-input form-select">
                        <option value="">— Seleccionar vivero —</option>
                        @foreach($viveros as $v)
                            <option value="{{ $v->id }}"
                                {{ old('vivero_id', $labor->vivero_id) == $v->id ? 'selected' : '' }}>
                                
                                {{ $v->codigo }}
                                @if(isset($v->nombre))
                                    — {{ $v->nombre }}
                                @endif
                                — {{ $v->municipio }}
                            </option>
                        @endforeach
                    </select>
                    @error('vivero_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- PRODUCTO --}}
                <div>
                    <label class="form-label">Producto de Control</label>
                    <select name="producto_control_id" class="form-input form-select">
                        <option value="">— Seleccionar producto —</option>
                        @foreach($productosControl as $pc)
                            <option value="{{ $pc->id }}"
                                {{ old('producto_control_id', $labor->producto_control_id) == $pc->id ? 'selected' : '' }}>
                                [{{ ucfirst($pc->tipo) }}] {{ $pc->nombre_producto }}
                            </option>
                        @endforeach
                    </select>
                    @error('producto_control_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- DESCRIPCIÓN --}}
                <div>
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" rows="4" class="form-input">{{ old('descripcion', $labor->descripcion) }}</textarea>
                    @error('descripcion')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- BOTONES --}}
                <div style="display:flex; gap:10px; padding-top:6px;">
                    <a href="{{ route('labores.index') }}" class="btn btn-secondary">
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-primary" style="background:var(--green-deep); border:none;">
                        Actualizar Labor
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>

@endsection