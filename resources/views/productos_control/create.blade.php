@extends('layouts.app')
@section('title', 'Nuevo Producto de Control')
@section('topbar-title', 'Productos de Control')
@section('topbar-subtitle', 'Nuevo')
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
        <h3 style="font-size:1.05rem;margin:0;">Registrar Producto de Control</h3>
    </div>
    <form method="POST" action="{{ route('productos-control.store') }}">
        @csrf
        <div class="card-body" style="display:flex;flex-direction:column;gap:18px;">

            {{-- Tipo selector --}}
            <div>
                <label class="form-label">Tipo de Producto</label>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:4px;">
                    @foreach(['hongo'=>['🍄','Hongo','badge-hongo'],'plaga'=>['🐛','Plaga','badge-plaga'],'fertilizante'=>['🌱','Fertilizante','badge-fertilizante']] as $val=>[$emoji,$label,$cls])
                    <label style="cursor:pointer;">
                        <input type="radio" name="tipo" value="{{ $val }}" {{ old('tipo')===$val ? 'checked' : '' }} style="display:none;" onchange="showFields('{{ $val }}')">
                        <div class="tipo-card" id="card-{{ $val }}" style="border:2px solid var(--border);border-radius:12px;padding:14px;text-align:center;transition:all .15s;">
                            <div style="font-size:1.5rem;">{{ $emoji }}</div>
                            <div style="font-size:.85rem;font-weight:600;margin-top:4px;">{{ $label }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('tipo')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            {{-- Common fields --}}
            <div>
                <label class="form-label">Nombre del Producto</label>
                <input type="text" name="nombre_producto" value="{{ old('nombre_producto') }}" class="form-input {{ $errors->has('nombre_producto') ? 'error':'' }}" placeholder="Nombre comercial">
                @error('nombre_producto')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div>
                    <label class="form-label">Registro ICA</label>
                    <input type="text" name="registro_ica" value="{{ old('registro_ica') }}" class="form-input {{ $errors->has('registro_ica') ? 'error':'' }}" placeholder="Número de registro">
                    @error('registro_ica')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label">Frecuencia Aplicación (días)</label>
                    <input type="number" name="frecuencia_aplicacion" value="{{ old('frecuencia_aplicacion') }}" class="form-input {{ $errors->has('frecuencia_aplicacion') ? 'error':'' }}" min="1" placeholder="Días entre aplicaciones">
                    @error('frecuencia_aplicacion')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div>
                <label class="form-label">Valor del Producto ($)</label>
                <input type="number" name="valor_producto" value="{{ old('valor_producto') }}" class="form-input {{ $errors->has('valor_producto') ? 'error':'' }}" min="0" step="0.01" placeholder="Precio">
                @error('valor_producto')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            {{-- Hongo fields --}}
            <div id="fields-hongo" style="display:none;flex-direction:column;gap:16px;padding:16px;background:var(--bg);border-radius:12px;">
                <div style="font-size:.8rem;font-weight:600;color:#7c3aed;text-transform:uppercase;letter-spacing:.05em;">🍄 Campos específicos — Hongo</div>
                <div>
                    <label class="form-label">Nombre del Hongo</label>
                    <input type="text" name="nombre_hongo" value="{{ old('nombre_hongo') }}" class="form-input" placeholder="Especie del hongo">
                </div>
                <div>
                    <label class="form-label">Período de Carencia (días)</label>
                    <input type="number" name="periodo_carencia_hongo" value="{{ old('periodo_carencia_hongo') }}" class="form-input" min="0">
                </div>
            </div>

            {{-- Plaga fields --}}
            <div id="fields-plaga" style="display:none;flex-direction:column;gap:16px;padding:16px;background:var(--bg);border-radius:12px;">
                <div style="font-size:.8rem;font-weight:600;color:#dc2626;text-transform:uppercase;letter-spacing:.05em;">🐛 Campos específicos — Plaga</div>
                <div>
                    <label class="form-label">Período de Carencia (días)</label>
                    <input type="number" name="periodo_carencia" value="{{ old('periodo_carencia') }}" class="form-input" min="0">
                </div>
            </div>

            {{-- Fertilizante fields --}}
            <div id="fields-fertilizante" style="display:none;flex-direction:column;gap:16px;padding:16px;background:var(--bg);border-radius:12px;">
                <div style="font-size:.8rem;font-weight:600;color:#16a34a;text-transform:uppercase;letter-spacing:.05em;">🌱 Campos específicos — Fertilizante</div>
                <div>
                    <label class="form-label">Fecha Última Aplicación</label>
                    <input type="date" name="fecha_ultima_aplicacion" value="{{ old('fecha_ultima_aplicacion') }}" class="form-input">
                </div>
            </div>

            <div style="display:flex;gap:10px;padding-top:4px;">
                <a href="{{ route('productos-control.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v14a2 2 0 01-2 2z"/></svg>
                    Guardar Producto
                </button>
            </div>
        </div>
    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
const colors = {hongo:'#7c3aed',plaga:'#dc2626',fertilizante:'#16a34a'};
function showFields(tipo) {
    ['hongo','plaga','fertilizante'].forEach(t => {
        const f = document.getElementById('fields-'+t);
        const c = document.getElementById('card-'+t);
        if (t === tipo) {
            f.style.display = 'flex';
            c.style.borderColor = colors[t];
            c.style.background = t==='hongo' ? '#f5f3ff' : t==='plaga' ? '#fff1f2' : '#f0fdf4';
        } else {
            f.style.display = 'none';
            c.style.borderColor = 'var(--border)';
            c.style.background = '';
        }
    });
}
// init on load if old()
const old = "{{ old('tipo') }}";
if (old) showFields(old);
</script>
@endpush