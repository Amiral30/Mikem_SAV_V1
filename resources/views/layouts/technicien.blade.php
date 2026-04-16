<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Technicien') - SAV Mikem</title>
    <link rel="icon" type="image/png" href="/images/minilogo.png">
    <link rel="stylesheet" href="/css/app.css?v={{ time() }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0f3460">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    
    <!-- Meta tags pour iOS (iPhone/iPad) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="SAV Mikem">
    <link rel="apple-touch-icon" href="/images/logom.png">

    @yield('styles')
    <style>
        /* Ajustement de précision pour le bouton de thème sur mobile */
        @media (max-width: 900px) {
            #theme-icon {
                transform: translateX(-2px);
            }
        }
    </style>
</head>
<body class="is-technician">
    <script>
        if(localStorage.getItem('theme') === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    </script>
    <div class="app-layout">
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Mikem Technologie" style="height: auto; width: 100%; max-width: 180px; object-fit: contain;">
                <small>Espace Technicien</small>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Principal</div>
                    <a href="{{ route('technicien.dashboard') }}" class="nav-link {{ request()->routeIs('technicien.dashboard') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="las la-chart-bar"></i></span> Dashboard
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Missions</div>
                    <a href="{{ route('technicien.missions.index') }}" class="nav-link {{ request()->routeIs('technicien.missions.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="las la-clipboard-list"></i></span> Mes Missions
                    </a>
                    <a href="{{ route('technicien.historique') }}" class="nav-link {{ request()->routeIs('technicien.historique') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="las la-history"></i></span> Historique
                    </a>
                <div class="nav-section">
                    <div class="nav-section-title">Paramètres</div>
                    <a href="{{ route('technicien.profile.index') }}" class="nav-link {{ request()->routeIs('technicien.profile.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="las la-user-circle"></i></span> Mon Profil
                    </a>
                </div>
            </nav>
            <div class="sidebar-footer">
                <div class="user-info">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Avatar" 
                             style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; margin-right: 12px; border: 2px solid var(--accent-primary);">
                    @else
                        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                    @endif
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
                <div class="topbar-actions">
                    <button id="theme-toggle" class="btn btn-secondary" style="border-radius: 50%; width: 38px; height: 38px; padding: 0; display: flex; align-items: center; justify-content: center; margin-right: 8px; line-height: 0; gap: 0; overflow: hidden;" title="Basculer le thème">
                        <i class="las la-moon" id="theme-icon" style="font-size: 1.35rem;"></i>
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
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        
        function setTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            
            // Mise à jour de l'icône
            if(theme === 'dark') {
                themeIcon.className = 'las la-sun';
                themeIcon.style.color = '#f1d468ff'; // Jaune soleil pour le mode sombre
            } else {
                themeIcon.className = 'las la-moon';
                themeIcon.style.color = 'var(--text-primary)'; // Couleur normale pour le mode clair
            }
        }

        // Initialisation immédiate au chargement
        const storedTheme = localStorage.getItem('theme') || 'light';
        setTheme(storedTheme);

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
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rows = document.querySelectorAll('tr[data-href]');
            rows.forEach(row => {
                row.classList.add('clickable-row');
                row.addEventListener('click', (e) => {
                    // Ne pas déclencher si on clique sur un lien ou un bouton à l'intérieur de la ligne
                    if (e.target.tagName !== 'A' && e.target.tagName !== 'BUTTON' && !e.target.closest('a') && !e.target.closest('button')) {
                        window.location.href = row.dataset.href;
                    }
                });
            });
        });
    </script>
</body>
</html>
