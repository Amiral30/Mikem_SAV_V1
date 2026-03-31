@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Vue d\'ensemble de vos opérations')

@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon purple">📋</div><div class="stat-value">{{ $stats['total_missions'] }}</div><div class="stat-label">Total Missions</div></div>
    <div class="stat-card"><div class="stat-icon blue">🔄</div><div class="stat-value">{{ $stats['missions_en_cours'] }}</div><div class="stat-label">En Cours</div></div>
    <div class="stat-card"><div class="stat-icon yellow">⏳</div><div class="stat-value">{{ $stats['missions_en_attente'] }}</div><div class="stat-label">En Attente</div></div>
    <div class="stat-card"><div class="stat-icon green">✅</div><div class="stat-value">{{ $stats['missions_terminees'] }}</div><div class="stat-label">Terminées</div></div>
    <div class="stat-card"><div class="stat-icon orange">⏸️</div><div class="stat-value">{{ $stats['missions_en_pause'] }}</div><div class="stat-label">En Pause</div></div>
    <div class="stat-card"><div class="stat-icon red">⛔</div><div class="stat-value">{{ $stats['missions_suspendues'] }}</div><div class="stat-label">Suspendues</div></div>
    <div class="stat-card"><div class="stat-icon green">👨‍🔧</div><div class="stat-value">{{ $stats['techniciens_disponibles'] }}<span style="font-size:0.9rem;color:var(--text-muted)">/{{ $stats['total_techniciens'] }}</span></div><div class="stat-label">Techniciens Disponibles</div></div>
</div>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <div class="card">
        <div class="card-header">
            <h3>📋 Missions Récentes</h3>
            <a href="{{ route('admin.missions.create') }}" class="btn btn-primary btn-sm">+ Nouvelle</a>
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
                            @if($mission->chefEquipe)<span style="font-size:0.8rem">👑 {{ $mission->chefEquipe->name }}</span>@endif
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
            <h3>👨‍🔧 Techniciens</h3>
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
