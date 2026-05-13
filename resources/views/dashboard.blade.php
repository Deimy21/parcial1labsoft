@extends('layouts.app')
@section('title', 'Dashboard')
@section('topbar-title', 'Dashboard')

@section('content')

{{-- Welcome banner --}}
<div style="background:linear-gradient(135deg, var(--green-deep) 0%, var(--green-mid) 100%); border-radius:18px; padding:28px 32px; margin-bottom:28px; position:relative; overflow:hidden;">
    <div style="position:absolute;top:-30px;right:-20px;width:180px;height:180px;background:rgba(255,255,255,.05);border-radius:50%;"></div>
    <div style="position:absolute;bottom:-50px;right:80px;width:120px;height:120px;background:rgba(255,255,255,.04);border-radius:50%;"></div>
    <h1 style="color:#fff;font-size:1.5rem;margin:0 0 6px;">🌿 Sistema de Viveros</h1>
    <p style="color:rgba(255,255,255,.7);font-size:.9rem;margin:0;">Administración completa de productores, fincas, viveros y labores agrícolas.</p>
</div>

{{-- Stats grid --}}
<div style="display:grid; grid-template-columns:repeat(5,1fr); gap:16px; margin-bottom:28px;">

    @foreach([
        ['label'=>'Productores','val'=>$stats['productores'],'icon'=>'👨‍🌾','bg'=>'#e8f5e9','color'=>'#2e7d32','link'=>route('productores.index')],
        ['label'=>'Viveros','val'=>$stats['viveros'],'icon'=>'🌱','bg'=>'#f3e5f5','color'=>'#6a1b9a','link'=>route('viveros.index')],
        ['label'=>'Labores','val'=>$stats['labores'],'icon'=>'📋','bg'=>'#fff3e0','color'=>'#e65100','link'=>route('labores.index')],
        ['label'=>'Productos Control','val'=>$stats['productos_control'],'icon'=>'⚗️','bg'=>'#fce4ec','color'=>'#880e4f','link'=>route('productos-control.index')],
    ] as $s)
    <a href="{{ $s['link'] }}" style="text-decoration:none;">
        <div class="stat-card" style="transition:all .15s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)';" onmouseout="this.style.transform='';this.style.boxShadow='';">
            <div style="width:44px;height:44px;border-radius:12px;background:{{ $s['bg'] }};display:flex;align-items:center;justify-content:center;font-size:1.2rem;margin-bottom:12px;">{{ $s['icon'] }}</div>
            <div style="font-size:1.8rem;font-weight:700;color:{{ $s['color'] }};font-family:'Playfair Display',serif;line-height:1;">{{ $s['val'] }}</div>
            <div style="font-size:.78rem;color:var(--text-muted);margin-top:4px;font-weight:500;">{{ $s['label'] }}</div>
        </div>
    </a>
    @endforeach
</div>

<div style="display:grid; grid-template-columns:1fr 320px; gap:20px; align-items:start;">

    {{-- Recent labores --}}
    <div class="card">
        <div class="card-header">
            <div>
                <h3 style="font-size:1.05rem;margin:0;">Últimas Labores</h3>
                <p style="font-size:.8rem;color:var(--text-muted);margin:2px 0 0;">Las 6 más recientes</p>
            </div>
            <a href="{{ route('labores.index') }}" class="btn btn-secondary btn-sm">Ver todas</a>
        </div>

        @if($ultimasLabores->isEmpty())
            <div style="text-align:center;padding:40px;color:var(--text-muted);font-size:.875rem;">Sin labores registradas</div>
        @else
        <div>
            @foreach($ultimasLabores as $labor)
            @php
                $tipo = $labor->productoControl->tipo ?? 'N/A';
                $colors = ['hongo'=>['#f5f3ff','#7c3aed'],'plaga'=>['#fff1f2','#dc2626'],'fertilizante'=>['#f0fdf4','#16a34a']];
                [$bg,$clr] = $colors[$tipo] ?? ['#f4f7f5','#6b7c73'];
            @endphp
            <div style="display:flex;align-items:center;gap:14px;padding:14px 20px;border-bottom:1px solid #f0f5f2;">
                <div style="width:40px;height:40px;border-radius:10px;background:{{ $bg }};display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">
                    {{ ['hongo'=>'🍄','plaga'=>'🐛','fertilizante'=>'🌱'][$tipo] ?? '⚗️' }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:.875rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $labor->descripcion }}</div>
                    <div style="font-size:.75rem;color:var(--text-muted);margin-top:1px;">
                        Vivero <strong>{{ $labor->vivero->codigo ?? '—' }}</strong>
                        · {{ $labor->vivero->finca->municipio ?? '' }}
                    </div>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    <div style="font-size:.78rem;font-weight:600;color:{{ $clr }};">{{ ucfirst($tipo) }}</div>
                    <div style="font-size:.72rem;color:var(--text-muted);">{{ \Carbon\Carbon::parse($labor->fecha)->format('d/m/Y') }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Productos por tipo --}}
    <div style="display:flex;flex-direction:column;gap:16px;">
        <div class="card">
            <div class="card-header">
                <h3 style="font-size:1.05rem;margin:0;">Productos por Tipo</h3>
            </div>
            <div style="padding:20px;display:flex;flex-direction:column;gap:14px;">
                @php $total = $productosPorTipo->sum(); @endphp
                @foreach(['hongo'=>['🍄','Hongos','#7c3aed','#f5f3ff'],'plaga'=>['🐛','Plagas','#dc2626','#fff1f2'],'fertilizante'=>['🌱','Fertilizantes','#16a34a','#f0fdf4']] as $tipo=>[$icon,$label,$clr,$bg])
                @php $val = $productosPorTipo[$tipo] ?? 0; $pct = $total > 0 ? round($val/$total*100) : 0; @endphp
                <div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                        <span style="font-size:.82rem;font-weight:600;">{{ $icon }} {{ $label }}</span>
                        <span style="font-size:.82rem;font-weight:700;color:{{ $clr }};">{{ $val }}</span>
                    </div>
                    <div style="height:8px;background:#f0f5f2;border-radius:4px;overflow:hidden;">
                        <div style="height:100%;width:{{ $pct }}%;background:{{ $clr }};border-radius:4px;transition:width .6s;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="card">
            @if(auth()->user()->rol == 'administrador')
                <div class="card-header">
                    <h3 style="font-size:1.05rem;margin:0;">Accesos Rápidos</h3>
                </div>
                <div style="padding:16px;display:flex;flex-direction:column;gap:8px;">
                    <a href="{{ route('productores.create') }}" class="btn btn-secondary" style="justify-content:flex-start;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                        Nuevo Productor
                    </a>
                    <a href="{{ route('labores.create') }}" class="btn btn-secondary" style="justify-content:flex-start;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                        Nueva Labor
                    </a>
                    <a href="{{ route('productos-control.create') }}" class="btn btn-secondary" style="justify-content:flex-start;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                        Nuevo Producto Control
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
