@extends('layouts.admin')
@section('title', $technicien->name)
@section('page-title', $technicien->name)
@section('page-subtitle', 'Fiche du technicien')
@section('topbar-actions')
<a href="{{ route('admin.techniciens.export', $technicien) }}" class="btn btn-success btn-sm"><i class="las la-file-pdf"></i> Exporter PDF</a>
<a href="{{ route('admin.techniciens.edit', $technicien) }}" class="btn btn-secondary btn-sm"><i class="las la-pen"></i> Modifier</a>
@endsection

@section('content')
<div class="mission-detail-grid">
    <div>
        <div class="card mb-3">
            <div class="card-header"><h3>👤 Informations</h3><span class="badge {{ $technicien->disponible?'badge-disponible':'badge-occupe' }}">{{ $technicien->disponible?'Disponible':'En mission' }}</span></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div class="detail-section"><h4>Nom</h4><div class="detail-value">{{ $technicien->name }}</div></div>
                    <div class="detail-section"><h4>Email</h4><div class="detail-value">{{ $technicien->email }}</div></div>
                    <div class="detail-section"><h4>Téléphone</h4><div class="detail-value">{{ $technicien->telephone ?? 'Non renseigné' }}</div></div>
                    <div class="detail-section"><h4>Inscrit le</h4><div class="detail-value">{{ $technicien->created_at->format('d/m/Y') }}</div></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h3>📋 Missions</h3></div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Mission</th><th>Type</th><th>Date</th><th>Statut</th><th>Action</th></tr></thead>
                    <tbody>
                        @forelse($technicien->missions as $mission)
                        <tr>
                            <td><a href="{{ route('admin.missions.show', $mission) }}" style="color:var(--text-primary);font-weight:600;">{{ $mission->titre }}</a></td>
                            <td>{{ $mission->type_mission }}</td>
                            <td>{{ $mission->date_mission->format('d/m/Y') }}</td>
                            <td><span class="badge {{ $mission->statut_class }}">{{ $mission->statut_label }}</span></td>
                            <td><a href="{{ route('admin.missions.show', $mission) }}" class="btn btn-secondary btn-sm"><i class="las la-eye"></i> Détails</a></td>
                        </tr>
                        @empty<tr><td colspan="5" class="text-center text-muted" style="padding:30px;">Aucune mission</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div>
        <div class="card">
            <div class="card-header"><h3>📊 Stats</h3></div>
            <div class="card-body">
                <div class="detail-section"><h4>Total missions</h4><div class="stat-value" style="font-size:1.5rem;">{{ $technicien->missions->count() }}</div></div>
                <div class="detail-section"><h4>Rapports soumis</h4><div class="stat-value" style="font-size:1.5rem;">{{ $technicien->rapports->count() }}</div></div>
                <hr style="border-color:var(--border-color);margin:20px 0;">
                <div class="detail-section"><h4>Gains Déplacements</h4><div class="stat-value" style="font-size:1.8rem;color:var(--accent-primary);">{{ number_format($technicien->missions->sum('prix_deplacement'), 0, ',', ' ') }} <span style="font-size:0.9rem;color:var(--text-muted)">FCFA</span></div></div>
            </div>
        </div>
    </div>
</div>
@endsection
