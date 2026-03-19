@extends('layouts.app')
@section('title', 'Editar Producto')
@section('topbar-title', 'Productos de Control')
@section('topbar-subtitle', 'Editar')
@section('topbar-actions')
    <a href="{{ route('productos-control.index') }}" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Volver
    </a>
@endsection

@section('content')
<div style="max-width:640px;">
<div class="card">
    <div class="card-header">
        <div>
            <h3 style="font-size:1.05rem;margin:0;">Editar Producto de Control</h3>
            @php $badgeClass = match($productoControl->tipo) {'hongo'=>'badge-hongo','plaga'=>'badge-plaga','fertilizante'=>'badge-fertilizante',default=>'badge-green'}; @endphp
            <span class="badge {{ $badgeClass }}" style="margin-top:6px;">{{ ucfirst($productoControl->tipo) }}</span>
        </div>
    </div>
    <form method="POST" action="{{ route('productos-control.update', $productoControl) }}">
        @csrf @method('PUT')
        <div class="card-body" style="display:flex;flex-direction:column;gap:18px;">

            <div>
                <label class="form-label">Nombre del Producto</label>
                <input type="text" name="nombre_producto" value="{{ old('nombre_producto', $productoControl->nombre_producto) }}" class="form-input {{ $errors->has('nombre_producto') ? 'error':'' }}">
                @error('nombre_producto')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div>
                    <label class="form-label">Registro ICA</label>
                    <input type="text" name="registro_ica" value="{{ old('registro_ica', $productoControl->registro_ica) }}" class="form-input {{ $errors->has('registro_ica') ? 'error':'' }}">
                    @error('registro_ica')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label">Frecuencia Aplicación (días)</label>
                    <input type="number" name="frecuencia_aplicacion" value="{{ old('frecuencia_aplicacion', $productoControl->frecuencia_aplicacion) }}" class="form-input" min="1">
                    @error('frecuencia_aplicacion')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div>
                <label class="form-label">Valor del Producto ($)</label>
                <input type="number" name="valor_producto" value="{{ old('valor_producto', $productoControl->valor_producto) }}" class="form-input" min="0" step="0.01">
            </div>

            {{-- Specific fields by type --}}
            @if($productoControl->tipo === 'hongo')
            <div style="padding:16px;background:var(--bg);border-radius:12px;border:2px solid #e9d5ff;">
                <div style="font-size:.8rem;font-weight:600;color:#7c3aed;margin-bottom:12px;text-transform:uppercase;">🍄 Datos del Hongo</div>
                <div style="display:flex;flex-direction:column;gap:14px;">
                    <div>
                        <label class="form-label">Nombre del Hongo</label>
                        <input type="text" name="nombre_hongo" value="{{ old('nombre_hongo', $productoControl->nombre_hongo) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Período de Carencia (días)</label>
                        <input type="number" name="periodo_carencia" value="{{ old('periodo_carencia', $productoControl->periodo_carencia) }}" class="form-input" min="0">
                    </div>
                </div>
            </div>
            @elseif($productoControl->tipo === 'plaga')
            <div style="padding:16px;background:var(--bg);border-radius:12px;border:2px solid #fecaca;">
                <div style="font-size:.8rem;font-weight:600;color:#dc2626;margin-bottom:12px;text-transform:uppercase;">🐛 Datos de Plaga</div>
                <div>
                    <label class="form-label">Período de Carencia (días)</label>
                    <input type="number" name="periodo_carencia" value="{{ old('periodo_carencia', $productoControl->periodo_carencia) }}" class="form-input" min="0">
                </div>
            </div>
            @elseif($productoControl->tipo === 'fertilizante')
            <div style="padding:16px;background:var(--bg);border-radius:12px;border:2px solid #bbf7d0;">
                <div style="font-size:.8rem;font-weight:600;color:#16a34a;margin-bottom:12px;text-transform:uppercase;">🌱 Datos de Fertilizante</div>
                <div>
                    <label class="form-label">Fecha Última Aplicación</label>
                    <input type="date" name="fecha_ultima_aplicacion" value="{{ old('fecha_ultima_aplicacion', $productoControl->fecha_ultima_aplicacion) }}" class="form-input">
                </div>
            </div>
            @endif

            <div style="display:flex;gap:10px;padding-top:4px;">
                <a href="{{ route('productos-control.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar Producto</button>
            </div>
        </div>
    </form>
</div>
</div>
@endsection