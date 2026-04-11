@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Vue d\'ensemble de vos opérations')

@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon purple"><i class="las la-clipboard-list"></i></div><div class="stat-value">{{ $stats['total_missions'] }}</div><div class="stat-label">Total Missions</div></div>
    <div class="stat-card"><div class="stat-icon blue"><i class="las la-sync"></i></div><div class="stat-value">{{ $stats['missions_en_cours'] }}</div><div class="stat-label">En Cours</div></div>
    <div class="stat-card"><div class="stat-icon green"><i class="las la-check-circle"></i></div><div class="stat-value">{{ $stats['missions_terminees'] }}</div><div class="stat-label">Terminées</div></div>
    <div class="stat-card"><div class="stat-icon orange"><i class="las la-user-check"></i></div><div class="stat-value">{{ $stats['techniciens_disponibles'] }}<span style="font-size:0.9rem;color:var(--text-muted)">/{{ $stats['total_techniciens'] }}</span></div><div class="stat-label">Techniciens Disponibles</div></div>
</div>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 30px;">
    <!-- Chart: Courbe d'évolution -->
    <div class="card">
        <div class="card-header">
            <h3><i class="las la-chart-area"></i> Évolution des Nouvelles Missions (7 jours)</h3>
        </div>
        <div class="card-body" style="height: 300px; padding: 20px;">
            <canvas id="lineChart"></canvas>
        </div>
    </div>
    <!-- Chart: Donut Répartition -->
    <div class="card">
        <div class="card-header">
            <h3><i class="las la-chart-pie"></i> Répartition des Statuts</h3>
        </div>
        <div class="card-body" style="height: 300px; padding: 20px; display: flex; justify-content: center;">
            <canvas id="donutChart"></canvas>
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <div class="card">
        <div class="card-header">
            <h3><i class="las la-list"></i> Missions Récentes</h3>
            <a href="{{ route('admin.missions.create') }}" class="btn btn-primary btn-sm"><i class="las la-plus"></i> Nouvelle</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Mission</th><th>Type</th><th>Date</th><th>Statut</th><th>Équipe</th></tr></thead>
                <tbody>
                    @forelse($recentMissions as $mission)
                    <tr>
                        <td><a href="{{ route('admin.missions.show', $mission) }}" style="color:var(--text-primary);font-weight:600;">{{ Str::limit($mission->titre, 30) }}</a></td>
                        <td>{{ $mission->type_mission }}</td>
                        <td>{{ $mission->date_mission->format('d/m/Y') }}</td>
                        <td><span class="badge {{ $mission->statut_class }}">{{ $mission->statut_label }}</span></td>
                        <td>
                            @if($mission->chefEquipe)<span style="font-size:0.8rem"><i class="las la-crown text-warning"></i> {{ $mission->chefEquipe->name }}</span>@endif
                            @if($mission->techniciens->count() > 1)<span class="text-muted" style="font-size:0.75rem">+{{ $mission->techniciens->count() - 1 }}</span>@endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted" style="padding:30px">Aucune mission</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3><i class="las la-users"></i> Techniciens</h3>
            <a href="{{ route('admin.techniciens.index') }}" class="btn btn-secondary btn-sm">Voir tout</a>
        </div>
        <div class="card-body" style="padding:12px;">
            @forelse($techniciens as $tech)
            <div class="team-member">
                <div class="member-avatar">{{ strtoupper(substr($tech->name, 0, 2)) }}</div>
                <div style="flex:1;min-width:0;"><div style="font-weight:600;font-size:0.88rem;">{{ $tech->name }}</div><div style="font-size:0.75rem;color:var(--text-muted);">{{ $tech->missions_count }} mission(s)</div></div>
                <span class="badge {{ $tech->disponible ? 'badge-disponible' : 'badge-occupe' }}">{{ $tech->disponible ? 'Dispo' : 'Occupé' }}</span>
            </div>
            @empty
            <p class="text-center text-muted" style="padding:20px">Aucun technicien</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configuration Chart.js globale au thème (gère le passage Clair/Sombre)
    const textColor = document.documentElement.getAttribute('data-theme') === 'dark' ? '#cbd5e1' : '#64748b';
    Chart.defaults.color = textColor;
    Chart.defaults.font.family = "'Inter', sans-serif";

    // 1. Chart en Courbe pleine (Line/Area)
    const ctxLine = document.getElementById('lineChart').getContext('2d');
    
    // Dégradé ultra design
    const gradientLine = ctxLine.createLinearGradient(0, 0, 0, 300);
    gradientLine.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
    gradientLine.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartDates) !!},
            datasets: [{
                label: 'Missions Créées',
                data: {!! json_encode($chartMissions) !!},
                borderColor: '#3b82f6',
                backgroundColor: gradientLine,
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#3b82f6',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4 // Courbe fluide très Apple-like
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: 'rgba(15, 23, 42, 0.9)', padding: 12, cornerRadius: 8 }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(150, 150, 150, 0.1)', drawBorder: false }, ticks: { precision: 0 } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Chart Donut (Répartition Statuts)
    const ctxDonut = document.getElementById('donutChart').getContext('2d');
    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: ['En cours', 'En attente', 'Terminées', 'Pause'],
            datasets: [{
                data: [
                    {{ $stats['missions_en_cours'] }}, 
                    {{ $stats['missions_en_attente'] }}, 
                    {{ $stats['missions_terminees'] }}, 
                    {{ $stats['missions_en_pause'] }}
                ],
                backgroundColor: [
                    '#3b82f6', // bleu
                    '#f59e0b', // jaune
                    '#10b981', // vert
                    '#f97316'  // orange
                ],
                borderWidth: 0,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '78%', // Épaisseur fine premium
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true, padding: 20 } },
                tooltip: { backgroundColor: 'rgba(15, 23, 42, 0.9)', padding: 12 }
            }
        }
    });

    // Observer pour mettre à jour les couleurs Chart si on clique sur la lune "Mode Sombre"
    document.getElementById('theme-toggle').addEventListener('click', () => {
        setTimeout(() => {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            Chart.defaults.color = isDark ? '#cbd5e1' : '#64748b';
            Chart.instances.forEach(chart => chart.update());
        }, 100);
    });
</script>
@endsection
