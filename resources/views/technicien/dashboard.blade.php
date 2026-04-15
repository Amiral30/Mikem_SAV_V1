@extends('layouts.technicien')
@section('title', 'Dashboard')
@section('page-title', 'Mon Dashboard')
@section('page-subtitle', 'Bienvenue, ' . auth()->user()->name)

@section('content')
<div class="stats-grid">
    <a href="{{ route('technicien.missions.index') }}" class="stat-card-link">
        <div class="stat-card"><div class="stat-icon purple"><i class="las la-clipboard-list"></i></div><div class="stat-value">{{ $stats['total_assignees'] }}</div><div class="stat-label">Missions Totales</div></div>
    </a>
    <a href="{{ route('technicien.missions.index', ['statut' => 'en_attente']) }}" class="stat-card-link">
        <div class="stat-card"><div class="stat-icon yellow"><i class="las la-hourglass-half"></i></div><div class="stat-value">{{ $stats['en_attente'] }}</div><div class="stat-label">En Attente</div></div>
    </a>
    <a href="{{ route('technicien.missions.index', ['statut' => 'en_cours']) }}" class="stat-card-link">
        <div class="stat-card"><div class="stat-icon blue"><i class="las la-sync"></i></div><div class="stat-value">{{ $stats['en_cours'] }}</div><div class="stat-label">En Cours</div></div>
    </a>
    <a href="{{ route('technicien.missions.index', ['statut' => 'terminee']) }}" class="stat-card-link">
        <div class="stat-card"><div class="stat-icon green"><i class="las la-check-circle"></i></div><div class="stat-value">{{ $stats['terminees'] }}</div><div class="stat-label">Terminées</div></div>
    </a>
</div>
<div class="card">
    <div class="card-header"><h3><i class="las la-list"></i> Missions Actives</h3><a href="{{ route('technicien.missions.index') }}" class="btn btn-secondary btn-sm">Voir tout</a></div>
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>Mission</th><th>Type</th><th>Adresse</th><th>Date</th><th>Statut</th><th>Rôle</th><th>Action</th></tr></thead>
            <tbody>
                @forelse($missions as $mission)
                <tr data-href="{{ route('technicien.missions.show', $mission) }}">
                    <td data-label="Mission"><strong>{{ Str::limit($mission->titre, 30) }}</strong></td>
                    <td data-label="Type">{{ $mission->type_mission }}</td>
                    <td data-label="Adresse">{{ Str::limit($mission->adresse, 25) }}</td>
                    <td data-label="Date">{{ $mission->date_mission->format('d/m/Y') }}</td>
                    <td data-label="Statut">
                        <span class="badge {{ $mission->statut_class }}">
                            @switch($mission->statut)
                                @case('en_attente') <i class="las la-clock"></i> @break
                                @case('acceptee') <i class="las la-user-check"></i> @break
                                @case('en_cours') <i class="las la-tools"></i> @break
                                @case('en_pause') <i class="las la-pause-circle"></i> @break
                                @case('suspendue') <i class="las la-stop-circle"></i> @break
                                @case('soumis') <i class="las la-file-alt"></i> @break
                                @case('a_modifier') <i class="las la-exclamation-triangle"></i> @break
                                @case('terminee') <i class="las la-check-double"></i> @break
                                @default <i class="las la-info-circle"></i>
                            @endswitch
                            {{ $mission->statut_label }}
                        </span>
                    </td>
                    <td data-label="Rôle">@if($mission->pivot->is_chef_equipe)<span class="chef-badge"><i class="las la-crown text-warning"></i> Chef</span>@else<span class="text-muted">Membre</span>@endif</td>
                    <td data-label="Action"><a href="{{ route('technicien.missions.show', $mission) }}" class="btn btn-primary btn-sm">Voir</a></td>
                </tr>
                @empty<tr><td colspan="7" class="text-center text-muted" style="padding:40px;">Aucune mission active.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
