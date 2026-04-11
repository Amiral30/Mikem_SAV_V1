@extends('layouts.admin')
@section('title', $mission->titre)
@section('page-title', $mission->titre)
@section('page-subtitle', 'Détails de la mission #' . $mission->id)

@section('topbar-actions')
@if($mission->statut === 'en_attente')
<div class="btn-group">
    <a href="{{ route('admin.missions.edit', $mission) }}" class="btn btn-secondary btn-sm"><i class="las la-pen"></i> Modifier</a>
    <form action="{{ route('admin.missions.destroy', $mission) }}" method="POST" onsubmit="return confirm('Supprimer ?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="las la-trash"></i> Supprimer</button></form>
</div>
@endif
@endsection

@section('content')
<div class="mission-detail-grid">
    <div>
        <div class="card mb-3">
            <div class="card-header"><h3><i class="las la-info-circle"></i> Informations</h3><span class="badge {{ $mission->statut_class }}" style="font-size:0.85rem;padding:6px 16px;">{{ $mission->statut_label }}</span></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div class="detail-section"><h4>Type</h4><div class="detail-value">{{ $mission->type_mission }}</div></div>
                    <div class="detail-section"><h4>Date</h4><div class="detail-value">{{ $mission->date_mission->format('d/m/Y') }}</div></div>
                    <div class="detail-section"><h4>Adresse</h4><div class="detail-value">{{ $mission->adresse }}</div></div>
                    <div class="detail-section"><h4>Prix déplacement</h4><div class="detail-value">{{ $mission->prix_deplacement ? number_format($mission->prix_deplacement, 0, ',', ' ').' Fcfa' : 'Non défini' }}</div></div>
                </div>
                <div class="detail-section"><h4>Description</h4><div class="detail-value">{{ $mission->description }}</div></div>
                <div class="detail-section"><h4>Créée par</h4><div class="detail-value">{{ $mission->createur->name ?? 'N/A' }} · {{ $mission->created_at->format('d/m/Y H:i') }}</div></div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h3><i class="las la-file-alt"></i> Rapports</h3></div>
            <div class="card-body">
                @forelse($mission->rapports as $rapport)
                <div style="padding:16px;background:var(--bg-input);border-radius:var(--radius-sm);margin-bottom:16px;border-left:3px solid var(--accent-primary);">
                    <div style="display:flex;justify-content:space-between;margin-bottom:12px;"><strong>{{ $rapport->user->name }}</strong><span class="text-muted" style="font-size:0.8rem;">{{ $rapport->created_at->format('d/m/Y H:i') }}</span></div>
                    <div class="detail-section"><h4>Déroulement</h4><div class="detail-value">{{ $rapport->deroulement }}</div></div>
                    @if($rapport->difficultes)<div class="detail-section"><h4>Difficultés</h4><div class="detail-value">{{ $rapport->difficultes }}</div></div>@endif
                    <div class="detail-section"><h4>Actions réalisées</h4><div class="detail-value">{{ $rapport->actions_realisees }}</div></div>
                    @if($rapport->fichiers && count($rapport->fichiers) > 0)
                    <div class="detail-section">
                        <h4>Pièces jointes</h4>
                        <div class="file-list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; margin-top: 10px;">
                            @foreach($rapport->fichiers as $f)
                            <div class="file-item" style="display: flex; align-items: center; padding: 10px; background: var(--bg-secondary); border-radius: 6px; border: 1px solid var(--border-color);">
                                <span style="margin-right: 10px; color: var(--accent-primary); font-size: 1.2rem;">
                                    @if(str_contains($f['type'] ?? '', 'image')) <i class="las la-image"></i>
                                    @elseif(str_contains($f['type'] ?? '', 'pdf')) <i class="las la-file-pdf"></i>
                                    @else <i class="las la-file"></i>
                                    @endif
                                </span>
                                <div style="flex: 1; overflow: hidden;">
                                    <a href="{{ url('storage/'.$f['path']) }}" target="_blank" style="font-weight: 500; font-size: 0.85rem; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $f['name'] }}">
                                        {{ $f['name'] }}
                                    </a>
                                    @if(isset($f['original_name']) && $f['name'] !== $f['original_name'])
                                    <small class="text-muted" style="font-size: 0.7rem; display: block;">{{ $f['original_name'] }}</small>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @empty
                <p class="text-center text-muted" style="padding:20px;">Aucun rapport soumis.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div>
        <div class="card">
            <div class="card-header"><h3><i class="las la-users"></i> Équipe</h3><span class="badge badge-info">{{ $mission->is_groupe?'Groupe':'Individuel' }}</span></div>
            <div class="card-body" style="padding:12px;">
                <ul class="team-list">
                    @forelse($mission->techniciens as $tech)
                    <li class="team-member">
                        <div class="member-avatar">{{ strtoupper(substr($tech->name, 0, 2)) }}</div>
                        <div style="flex:1;"><div style="font-weight:600;font-size:0.88rem;">{{ $tech->name }} @if($tech->pivot->is_chef_equipe)<span class="chef-badge"><i class="las la-crown"></i> Chef</span>@endif</div><div style="font-size:0.75rem;color:var(--text-muted);">{{ $tech->email }}</div></div>
                        <span class="badge {{ $tech->disponible?'badge-disponible':'badge-occupe' }}">{{ $tech->disponible?'Dispo':'Occupé' }}</span>
                    </li>
                    @empty
                    <p class="text-muted text-center">Aucun technicien assigné</p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
