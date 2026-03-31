@extends('layouts.admin')

@section('title', $technicien->name)
@section('page-title', $technicien->name)
@section('page-subtitle', 'Fiche du technicien')

@section('topbar-actions')
<div class="btn-group">
    <a href="{{ route('admin.techniciens.edit', $technicien) }}" class="btn btn-secondary btn-sm">✏️ Modifier</a>
</div>
@endsection

@section('content')
<div class="mission-detail-grid">
    <div>
        <!-- Info -->
        <div class="card mb-3">
            <div class="card-header">
                <h3>👤 Informations</h3>
                <span class="badge {{ $technicien->disponible ? 'badge-disponible' : 'badge-occupe' }}">
                    {{ $technicien->disponible ? 'Disponible' : 'Occupé' }}
                </span>
            </div>
            <div class="card-body">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="detail-section">
                        <h4>Nom</h4>
                        <div class="detail-value">{{ $technicien->name }}</div>
                    </div>
                    <div class="detail-section">
                        <h4>Email</h4>
                        <div class="detail-value">{{ $technicien->email }}</div>
                    </div>
                    <div class="detail-section">
                        <h4>Téléphone</h4>
                        <div class="detail-value">{{ $technicien->telephone ?? 'Non renseigné' }}</div>
                    </div>
                    <div class="detail-section">
                        <h4>Inscrit le</h4>
                        <div class="detail-value">{{ $technicien->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Missions -->
        <div class="card">
            <div class="card-header">
                <h3>📋 Missions assignées</h3>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mission</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($technicien->missions as $mission)
                        <tr>
                            <td><a href="{{ route('admin.missions.show', $mission) }}" style="color:var(--text-primary); font-weight:600;">{{ $mission->titre }}</a></td>
                            <td>{{ $mission->type_mission }}</td>
                            <td>{{ $mission->date_mission->format('d/m/Y') }}</td>
                            <td><span class="badge {{ $mission->statut_class }}">{{ $mission->statut_label }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted" style="padding:30px;">Aucune mission assignée</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>
        <!-- Stats -->
        <div class="card mb-3">
            <div class="card-header"><h3>📊 Statistiques</h3></div>
            <div class="card-body">
                <div class="detail-section">
                    <h4>Total missions</h4>
                    <div class="stat-value" style="font-size:1.5rem;">{{ $technicien->missions->count() }}</div>
                </div>
                <div class="detail-section">
                    <h4>Rapports soumis</h4>
                    <div class="stat-value" style="font-size:1.5rem;">{{ $technicien->rapports->count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
