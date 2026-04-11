<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') - SAV Mikem</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
</head>
<body>
    <script>
        // Éviter le flash blanc au chargement
        if(localStorage.getItem('theme') === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    </script>

    <div class="app-layout">
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Mikem Technologie" style="height: auto; width: 100%; max-width: 180px; object-fit: contain;">
                <small>Panneau d'Administration</small>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Principal</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="las la-chart-bar"></i></span> Dashboard
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Gestion</div>
                    <a href="{{ route('admin.missions.index') }}" class="nav-link {{ request()->routeIs('admin.missions.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="las la-clipboard-list"></i></span> Missions
                    </a>
                    <a href="{{ route('admin.techniciens.index') }}" class="nav-link {{ request()->routeIs('admin.techniciens.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="las la-tools"></i></span> Techniciens
                    </a>
                </div>
            </nav>
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                    <div class="user-details">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">Administrateur</div>
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
                <div class="topbar-actions">
                    <button id="theme-toggle" class="btn btn-secondary" style="border-radius: 50%; width: 38px; height: 38px; padding: 0; display: flex; align-items: center; justify-content: center; margin-right: 8px;" title="Basculer le thème">
                        <i class="las la-moon" id="theme-icon" style="font-size: 1.3rem;"></i>
                    </button>
                    @yield('topbar-actions')
                </div>
            </header>
            <div class="content-area">
                @if(session('success'))<div class="alert alert-success"><i class="las la-check-circle" style="font-size: 1.2rem;"></i> {{ session('success') }}</div>@endif
                @if(session('error'))<div class="alert alert-danger"><i class="las la-exclamation-circle" style="font-size: 1.2rem;"></i> {{ session('error') }}</div>@endif
                @if(session('info'))<div class="alert alert-info"><i class="las la-info-circle" style="font-size: 1.2rem;"></i> {{ session('info') }}</div>@endif
                @yield('content')
            </div>
        </main>
    </div>
    <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="las la-bars"></i></button>
    <script>
        // Gestion du Dark Mode
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        
        function setTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            if(theme === 'dark') {
                themeIcon.classList.remove('la-moon');
                themeIcon.classList.add('la-sun');
            } else {
                themeIcon.classList.remove('la-sun');
                themeIcon.classList.add('la-moon');
            }
        }

        // Appliquer l'icône correspondante au chargement
        const storedTheme = localStorage.getItem('theme') || 'light';
        setTheme(storedTheme);

        // Au clic : bascule
        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            setTheme(currentTheme === 'light' ? 'dark' : 'light');
        });

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
    </script>
    @yield('scripts')
</body>
</html>
