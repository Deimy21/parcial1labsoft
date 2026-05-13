<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Viveros') — Sistema de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --green-deep:   #1a4731;
            --green-mid:    #2d6e4e;
            --green-bright: #38a169;
            --green-light:  #ebf8f0;
            --amber:        #d97706;
            --amber-light:  #fef3c7;
            --red-soft:     #dc2626;
            --text-main:    #1a2e23;
            --text-muted:   #6b7c73;
            --bg:           #f4f7f5;
            --white:        #ffffff;
            --border:       #d4e4db;
        }
        * { font-family: 'DM Sans', sans-serif; }
        body { background: var(--bg); color: var(--text-main); }
        h1,h2,h3 { font-family: 'Playfair Display', serif; }

        /* Sidebar */
        .sidebar {
            background: var(--green-deep);
            width: 260px; min-height: 100vh;
            position: fixed; top:0; left:0; z-index:50;
            display:flex; flex-direction:column;
            box-shadow: 4px 0 20px rgba(0,0,0,.15);
        }
        .sidebar-logo {
            padding: 28px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        .sidebar-logo h2 {
            color:#fff; font-size:1.25rem; letter-spacing:-.01em;
        }
        .sidebar-logo span { color:#6ee7a8; font-size:.75rem; display:block; margin-top:2px; font-family:'DM Sans',sans-serif; font-weight:400; }
        .nav-section { padding: 16px 12px 4px; }
        .nav-label { color:rgba(255,255,255,.4); font-size:.65rem; text-transform:uppercase; letter-spacing:.1em; font-weight:600; padding: 0 12px; margin-bottom:4px; }
        .nav-item {
            display:flex; align-items:center; gap:12px;
            padding:10px 14px; border-radius:10px; margin-bottom:2px;
            color:rgba(255,255,255,.75); text-decoration:none;
            font-size:.875rem; font-weight:500; transition:all .15s;
        }
        .nav-item:hover { background:rgba(255,255,255,.1); color:#fff; }
        .nav-item.active { background:rgba(110,231,168,.15); color:#6ee7a8; }
        .nav-item svg { width:18px; height:18px; flex-shrink:0; }
        .nav-badge { margin-left:auto; background:rgba(255,255,255,.15); color:#fff; font-size:.7rem; padding:1px 8px; border-radius:20px; }

        /* Main content */
        .main-wrap { margin-left:260px; min-height:100vh; }
        .topbar {
            background:var(--white); border-bottom:1px solid var(--border);
            padding:0 32px; height:64px; display:flex; align-items:center; justify-content:space-between;
            position:sticky; top:0; z-index:40;
        }
        .topbar-title { font-size:1.1rem; font-weight:600; color:var(--text-main); display:flex; align-items:center; gap:10px; }
        .topbar-title span { color:var(--text-muted); font-weight:400; font-size:.9rem; }
        .page-body { padding: 32px; }

        /* Cards */
        .card {
            background:var(--white); border-radius:16px;
            border:1px solid var(--border);
            box-shadow:0 1px 3px rgba(0,0,0,.04);
        }
        .card-header { padding:20px 24px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
        .card-body { padding:24px; }

        /* Buttons */
        .btn { display:inline-flex; align-items:center; gap:8px; padding:9px 18px; border-radius:10px; font-size:.875rem; font-weight:600; cursor:pointer; border:none; text-decoration:none; transition:all .15s; }
        .btn-primary { background:var(--green-deep); color:#fff; }
        .btn-primary:hover { background:var(--green-mid); }
        .btn-secondary { background:var(--green-light); color:var(--green-deep); border:1px solid var(--border); }
        .btn-secondary:hover { background:#d4edd9; }
        .btn-danger { background: #f7c6d2; color:var(--red-soft); }
        .btn-danger:hover { background:#fecaca; }
        .btn-amber { background:var(--amber-light); color:var(--amber); }
        .btn-amber:hover { background:#fde68a; }
        .btn-sm { padding:6px 12px; font-size:.8rem; border-radius:8px; }
        .btn-icon { padding:7px; border-radius:8px; }

        /* Table */
        .table-wrap { overflow-x:auto; border-radius:12px; }
        table { width:100%; border-collapse:collapse; }
        thead th { background:var(--green-light); color:var(--green-deep); font-size:.75rem; text-transform:uppercase; letter-spacing:.07em; font-weight:700; padding:12px 16px; text-align:left; }
        thead th:first-child { border-radius:10px 0 0 0; }
        thead th:last-child { border-radius:0 10px 0 0; }
        tbody tr { border-bottom:1px solid #f0f5f2; transition:background .1s; }
        tbody tr:hover { background:#fafcfb; }
        tbody td { padding:13px 16px; font-size:.875rem; color:var(--text-main); vertical-align:middle; }
        tbody tr:last-child { border-bottom:none; }

        /* Badges */
        .badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:.75rem; font-weight:600; }
        .badge-hongo { background:#f3e8ff; color:#7c3aed; }
        .badge-plaga { background:#fee2e2; color:#dc2626; }
        .badge-fertilizante { background:#dcfce7; color:#16a34a; }
        .badge-green { background:var(--green-light); color:var(--green-deep); }
        .badge-amber { background:var(--amber-light); color:var(--amber); }

        /* Alert / flash */
        .flash-success { background:#dcfce7; border:1px solid #86efac; color:#166534; padding:14px 18px; border-radius:12px; margin-bottom:20px; display:flex; align-items:center; gap:10px; font-size:.875rem; font-weight:500; }
        .flash-error { background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:14px 18px; border-radius:12px; margin-bottom:20px; display:flex; align-items:center; gap:10px; font-size:.875rem; font-weight:500; }

        /* Form */
        .form-label { display:block; font-size:.8rem; font-weight:600; color:var(--text-main); margin-bottom:6px; text-transform:uppercase; letter-spacing:.05em; }
        .form-input { width:100%; padding:10px 14px; border:1.5px solid var(--border); border-radius:10px; font-size:.875rem; color:var(--text-main); background:var(--white); outline:none; transition:border .15s; }
        .form-input:focus { border-color:var(--green-bright); box-shadow:0 0 0 3px rgba(56,161,105,.1); }
        .form-input.error { border-color:#fca5a5; }
        .form-error { color:#dc2626; font-size:.78rem; margin-top:4px; }
        .form-select { appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 10px center; background-size:20px; padding-right:36px; }

        /* Avatar initials */
        .avatar { width:40px; height:40px; border-radius:10px; background:var(--green-light); color:var(--green-deep); display:inline-flex; align-items:center; justify-content:center; font-weight:700; font-size:.85rem; flex-shrink:0; }

        /* Stats */
        .stat-card { background:var(--white); border-radius:14px; border:1px solid var(--border); padding:20px 22px; }
        .stat-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; }

        /* Search */
        .search-wrap { position:relative; }
        .search-wrap input { padding-left:40px; }
        .search-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-muted); pointer-events:none; }

        /* Breadcrumb */
        .breadcrumb { display:flex; align-items:center; gap:6px; font-size:.8rem; color:var(--text-muted); margin-bottom:20px; }
        .breadcrumb a { color:var(--green-mid); text-decoration:none; }
        .breadcrumb a:hover { text-decoration:underline; }
        .breadcrumb span { color:var(--text-muted); }

        /* Confirm delete modal */
        .modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:100; display:none; align-items:center; justify-content:center; }
        .modal-overlay.open { display:flex; }
        .modal-box { background:#fff; border-radius:16px; padding:28px; max-width:400px; width:100%; box-shadow:0 20px 60px rgba(0,0,0,.2); }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <h2>🌿 Viveros</h2>
        <span>Sistema de Administración</span>
    </div>

    <nav style="flex:1; padding:8px 0; overflow-y:auto;">
        <div class="nav-section">
            <div class="nav-label">Principal</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                Dashboard
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-label">Gestión</div>
            <a href="{{ route('productores.index') }}" class="nav-item {{ request()->routeIs('productores.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                Productores
            </a>
            <a href="{{ route('viveros.index') }}" class="nav-item {{ request()->routeIs('viveros.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2a10 10 0 00-10 10 10 10 0 0010 10 10 10 0 0010-10A10 10 0 0012 2z"/><path d="M12 6v6l4 2"/></svg>
                Viveros
            </a>
            <a href="{{ route('labores.index') }}" class="nav-item {{ request()->routeIs('labores.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h4"/></svg>
                Labores
            </a>
            <a href="{{ route('productos-control.index') }}" class="nav-item {{ request()->routeIs('productos-control.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                Productos de Control
            </a>
        </div>

        {{-- ✅ NUEVO: Menú Reportes --}}
        <div class="nav-section">
            <div class="nav-label">Reportes</div>
            <a href="{{ route('reportes.index') }}" class="nav-item {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                Reportes
            </a>
        </div>

    </nav>

    <div style="padding:16px; border-top:1px solid rgba(255,255,255,.1);">
        <div style="font-size:.75rem; color:rgba(255,255,255,.4); text-align:center;">v1.0 &mdash; Laravel 12</div>
    </div>
</aside>

<!-- Main -->
<div class="main-wrap">
    <!-- Topbar -->
    <header class="topbar">

        <div class="topbar-title">
            @yield('topbar-title', 'Dashboard')

            @hasSection('topbar-subtitle')
                <span>/ @yield('topbar-subtitle')</span>
            @endif
        </div>

        <div style="display:flex; align-items:center; gap:16px;">

            {{-- BOTONES DE CADA VISTA --}}
            <div>
                @yield('topbar-actions')
            </div>

            {{-- INFO USUARIO --}}
            <div style="text-align:left;">
                <div style="font-size:.85rem; font-weight:600;">
                    {{ auth()->user()->name }}
                </div>

                <div style="font-size:.75rem; color:var(--text-muted);">
                    {{ auth()->user()->email }}
                </div>

                <div style="font-size:.75rem; color:var(--text-muted);">
                    {{ auth()->user()->rol }}
                </div>
            </div>

            {{-- LOGOUT --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button class="btn btn-danger btn-sm">
                    Cerrar sesión
                </button>
            </form>

        </div>

    </header>
    <!-- Body -->
    <main class="page-body">
        @if(session('success'))
            <div class="flash-success">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flash-error">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

<!-- Delete Confirm Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div style="text-align:center; margin-bottom:20px;">
            <div style="width:56px;height:56px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                <svg width="24" height="24" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24"><polyline points="3,6 5,6 21,6"/><path d="M19,6l-1,14a2,2,0,01-2,2H8a2,2,0,01-2-2L5,6"/><path d="M10,11v6M14,11v6"/><path d="M9,6V4a1,1,0,011-1h4a1,1,0,011,1v2"/></svg>
            </div>
            <h3 style="font-size:1.1rem;margin-bottom:6px;">¿Eliminar registro?</h3>
            <p style="font-size:.875rem;color:var(--text-muted);" id="deleteModalMsg">Esta acción no se puede deshacer.</p>
        </div>
        <div style="display:flex;gap:10px;">
            <button onclick="closeDeleteModal()" class="btn btn-secondary" style="flex:1;justify-content:center;">Cancelar</button>
            <form id="deleteModalForm" method="POST" style="flex:1;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;">Sí, eliminar</button>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(url, msg) {
    document.getElementById('deleteModalForm').action = url;
    document.getElementById('deleteModalMsg').textContent = msg || 'Esta acción no se puede deshacer.';
    document.getElementById('deleteModal').classList.add('open');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('open');
}
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>
@stack('scripts')
</body>
</html>