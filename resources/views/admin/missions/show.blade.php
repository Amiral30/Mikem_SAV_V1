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
        @if($errors->any())
        <div class="alert alert-danger" style="border-radius:8px;margin-bottom:16px;">
            <ul style="margin:0;padding-left:20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        {{-- === INFORMATIONS === --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="las la-info-circle"></i> Informations</h3>
                <span class="badge {{ $mission->statut_class }}" style="font-size:0.85rem;padding:6px 16px;">
                    @switch($mission->statut)
                        @case('en_attente') <i class="las la-clock"></i> @break
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
            </div>
            <div class="card-body">
                <div class="details-grid">
                    <div class="detail-section"><h4>Type</h4><div class="detail-value">{{ $mission->type_mission }}</div></div>
                    <div class="detail-section"><h4>Date</h4><div class="detail-value">{{ $mission->date_mission->format('d/m/Y') }}</div></div>
                    <div class="detail-section"><h4>Adresse</h4><div class="detail-value">{{ $mission->adresse }}</div></div>
                    <div class="detail-section"><h4>Prix déplacement</h4><div class="detail-value">{{ $mission->prix_deplacement ? number_format($mission->prix_deplacement, 0, ',', ' ').' Fcfa' : 'Non défini' }}</div></div>
                </div>
                <div class="detail-section"><h4>Description</h4><div class="detail-value">{{ $mission->description }}</div></div>
                <div class="detail-section"><h4>Créée par</h4><div class="detail-value">{{ $mission->createur->name ?? 'N/A' }} · {{ $mission->created_at->format('d/m/Y H:i') }}</div></div>
            </div>
        </div>

        {{-- === TIMELINE V13 === --}}
        <div class="card mb-3">
            <div class="card-header"><h3><i class="las la-stream"></i> Chronologie de la Mission</h3></div>
            <div class="card-body" style="padding:20px;">
                <div class="timeline-v13">
                    <div class="timeline-step {{ $mission->started_at ? 'done' : '' }}">
                        <div class="timeline-dot"><i class="las la-play"></i></div>
                        <div class="timeline-info">
                            <strong>Début de la mission</strong>
                            <span>{{ $mission->started_at ? $mission->started_at->format('d/m/Y à H:i') : '—' }}</span>
                        </div>
                    </div>
                    <div class="timeline-step {{ $mission->work_finished_at ? 'done' : '' }}">
                        <div class="timeline-dot"><i class="las la-flag-checkered"></i></div>
                        <div class="timeline-info">
                            <strong>Fin du travail technique</strong>
                            <span>{{ $mission->work_finished_at ? $mission->work_finished_at->format('d/m/Y à H:i') : '—' }}</span>
                        </div>
                    </div>
                    <div class="timeline-step {{ $mission->submitted_at ? 'done' : '' }}">
                        <div class="timeline-dot"><i class="las la-paper-plane"></i></div>
                        <div class="timeline-info">
                            <strong>Rapport soumis</strong>
                            <span>{{ $mission->submitted_at ? $mission->submitted_at->format('d/m/Y à H:i') : '—' }}</span>
                        </div>
                    </div>
                    <div class="timeline-step {{ $mission->validated_at ? 'done' : '' }}">
                        <div class="timeline-dot"><i class="las la-check-double"></i></div>
                        <div class="timeline-info">
                            <strong>Validation Admin</strong>
                            <span>{{ $mission->validated_at ? $mission->validated_at->format('d/m/Y à H:i') : '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- === RAPPORTS === --}}
        <div class="card mb-3">
            <div class="card-header"><h3><i class="las la-file-alt"></i> Rapport</h3></div>
            <div class="card-body">
                @forelse($mission->rapports as $rapport)
                <div style="padding:16px;background:var(--bg-input);border-radius:var(--radius-sm);margin-bottom:16px;border-left:3px solid var(--accent-primary);">
                    <div style="display:flex;justify-content:space-between;margin-bottom:12px;"><strong>{{ $rapport->user->name }}</strong><span class="text-muted" style="font-size:0.8rem;">{{ $rapport->created_at->format('d/m/Y H:i') }}</span></div>
                    <div class="detail-section"><h4>Déroulement</h4><div class="detail-value">{{ $rapport->deroulement }}</div></div>
                    @if($rapport->difficultes)<div class="detail-section"><h4>Difficultés</h4><div class="detail-value">{{ $rapport->difficultes }}</div></div>@endif
                    <div class="detail-section"><h4>Actions réalisées</h4><div class="detail-value">{{ $rapport->actions_realisees }}</div></div>

                    {{-- Fiche de passage --}}
                    @if($rapport->fiche_passage_path)
                    <div class="detail-section" style="margin-top:15px;">
                        <h4><i class="las la-file-signature"></i> Fiche de Passage Signée</h4>
                        <div style="margin-top:8px;">
                            @if(str_contains($rapport->fiche_passage_path, '.pdf'))
                                <a href="{{ asset('storage/'.$rapport->fiche_passage_path) }}" target="_blank" class="btn btn-secondary btn-sm"><i class="las la-file-pdf"></i> Télécharger le PDF</a>
                            @else
                                <a href="{{ asset('storage/'.$rapport->fiche_passage_path) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$rapport->fiche_passage_path) }}" style="max-width:100%;max-height:400px;border-radius:8px;border:1px solid var(--border-color);" alt="Fiche de passage">
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Pièces jointes --}}
                    @if($rapport->fichiers && count($rapport->fichiers) > 0)
                    <div class="detail-section" style="margin-top:15px;">
                        <h4>Pièces jointes</h4>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px;margin-top:10px;">
                            @foreach($rapport->fichiers as $f)
                            <div style="display:flex;align-items:center;padding:10px;background:var(--bg-secondary);border-radius:6px;border:1px solid var(--border-color);">
                                <span style="margin-right:10px;color:var(--accent-primary);font-size:1.2rem;">
                                    @if(str_contains($f['type'] ?? '', 'image')) <i class="las la-image"></i>
                                    @elseif(str_contains($f['type'] ?? '', 'pdf')) <i class="las la-file-pdf"></i>
                                    @else <i class="las la-file"></i>
                                    @endif
                                </span>
                                <a href="{{ url('storage/'.$f['path']) }}" target="_blank" style="font-weight:500;font-size:0.85rem;">{{ $f['name'] }}</a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Notes admin précédentes --}}
                    @if($rapport->admin_notes)
                        @if($mission->statut === 'terminee')
                        <div style="margin-top:15px; padding:12px; background:rgba(76,175,80,0.06); border-radius:8px; border-left:4px solid #4caf50;">
                            <strong style="color:#4caf50;"><i class="las la-check-circle"></i> Votre commentaire de validation :</strong>
                            <p style="margin:8px 0 0;">{{ $rapport->admin_notes }}</p>
                        </div>
                        @else
                        <div style="margin-top:15px; padding:12px; background:rgba(244,67,54,0.06); border-radius:8px; border-left:4px solid #f44336;">
                            <strong style="color:#f44336;"><i class="las la-comment-alt"></i> Votre dernière remarque :</strong>
                            <p style="margin:8px 0 0;">{{ $rapport->admin_notes }}</p>
                        </div>
                        @endif
                    @endif
                </div>
                @empty
                <p class="text-center text-muted" style="padding:20px;">Aucun rapport soumis.</p>
                @endforelse
            </div>
        </div>

        {{-- === ZONE DE VALIDATION ADMIN (V13) === --}}
        @if(in_array($mission->statut, ['soumis', 'a_modifier']))
        <div class="card mb-3" style="border: 2px solid var(--accent-primary);">
            <div class="card-header" style="background:rgba(var(--accent-primary-rgb,15,52,96),0.08);"><h3><i class="las la-gavel"></i> Décision de Validation</h3></div>
            <div class="card-body" style="padding:25px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    {{-- Valider --}}
                    <form action="{{ route('admin.missions.valider', $mission) }}" method="POST" onsubmit="return confirm('Confirmer la validation définitive de cette mission ?')">
                        @csrf
                        <textarea name="admin_notes" class="form-control mb-2" rows="2" placeholder="Commentaire de validation (optionnel)"></textarea>
                        <button type="submit" class="btn btn-success w-100" style="font-size:1rem;padding:14px;">
                            <i class="las la-check-double"></i> Valider la Mission ✅
                        </button>
                    </form>
                    {{-- Rejeter --}}
                    <form action="{{ route('admin.missions.rejeter', $mission) }}" method="POST">
                        @csrf
                        <textarea name="admin_notes" class="form-control mb-2" rows="2" placeholder="Indiquez les corrections à apporter..." required></textarea>
                        <button type="submit" class="btn btn-danger w-100" style="font-size:1rem;padding:14px;">
                            <i class="las la-undo"></i> Demander Correction ❌
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
    <div>
        {{-- === ÉQUIPE === --}}
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
