<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Technicien') - SAV Mikem</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="app-layout">
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <h2>🔧 SAV Mikem</h2>
                <small>Espace Technicien</small>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Principal</div>
                    <a href="{{ route('technicien.dashboard') }}" class="nav-link {{ request()->routeIs('technicien.dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">📊</span> Dashboard
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Missions</div>
                    <a href="{{ route('technicien.missions.index') }}" class="nav-link {{ request()->routeIs('technicien.missions.*') ? 'active' : '' }}">
                        <span class="nav-icon">📋</span> Mes Missions
                    </a>
                    <a href="{{ route('technicien.historique') }}" class="nav-link {{ request()->routeIs('technicien.historique') ? 'active' : '' }}">
                        <span class="nav-icon">📚</span> Historique
                    </a>
                </div>
            </nav>
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                    <div class="user-details">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">Technicien</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm btn-block">Déconnexion</button>
                </form>
            </div>
        </aside>
        <main class="main-content">
            <header class="topbar">
                <div class="topbar-title">
                    <h1>@yield('page-title', 'Dashboard')</h1>
                    <p>@yield('page-subtitle', '')</p>
                </div>
                <div class="topbar-actions">@yield('topbar-actions')</div>
            </header>
            <div class="content-area">
                @if(session('success'))<div class="alert alert-success">✅ {{ session('success') }}</div>@endif
                @if(session('error'))<div class="alert alert-danger">❌ {{ session('error') }}</div>@endif
                @if(session('info'))<div class="alert alert-info">ℹ️ {{ session('info') }}</div>@endif
                @yield('content')
            </div>
        </main>
    </div>
    <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
    </script>
    @yield('scripts')
</body>
</html>
