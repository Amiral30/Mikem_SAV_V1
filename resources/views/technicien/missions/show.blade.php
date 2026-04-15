@extends('layouts.technicien')
@section('title', $mission->titre)
@section('page-title', $mission->titre)
@section('page-subtitle', 'Détails de la mission')

@section('content')
@php
    $user = auth()->user();
    $pivot = $mission->techniciens->where('id', $user->id)->first();
    $isChef = $pivot ? $pivot->pivot->is_chef_equipe : false;
    $hasAccepted = $pivot ? $pivot->pivot->accepted : null;
    $isSolo = !$mission->is_groupe;
    $rapport = $mission->rapports->first();
@endphp

<div class="mission-detail-grid">
    <div>
        {{-- === NOTIFICATION ADMIN === --}}
        @if($rapport && $rapport->admin_notes)
            @if($mission->statut === 'terminee')
            {{-- Remarque de validation (VERT) --}}
            <div class="card mb-3" style="border: 2px solid #4caf50;">
                <div class="card-body" style="padding:20px;">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                        <i class="las la-check-circle" style="font-size:2rem;color:#4caf50;"></i>
                        <div>
                            <strong style="color:#4caf50;font-size:1.1rem;">Mission validée par l'Administration ✅</strong>
                            <p style="margin:4px 0 0;color:var(--text-secondary);">Votre rapport a été approuvé. Bravo !</p>
                        </div>
                    </div>
                    <div style="padding:15px;background:rgba(76,175,80,0.06);border-radius:8px;border-left:4px solid #4caf50;">
                        <strong>Commentaire de l'admin :</strong>
                        <p style="margin:8px 0 0;">{{ $rapport->admin_notes }}</p>
                    </div>
                </div>
            </div>
            @elseif($mission->statut === 'a_modifier')
            {{-- Demande de correction (ROUGE) --}}
            <div class="card mb-3" style="border: 2px solid #f44336;">
                <div class="card-body" style="padding:20px;">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                        <i class="las la-exclamation-circle" style="font-size:2rem;color:#f44336;"></i>
                        <div>
                            <strong style="color:#f44336;font-size:1.1rem;">Correction demandée par l'Administration</strong>
                            <p style="margin:4px 0 0;color:var(--text-secondary);">Corrigez votre rapport selon les remarques ci-dessous, puis re-soumettez-le.</p>
                        </div>
                    </div>
                    <div style="padding:15px;background:rgba(244,67,54,0.06);border-radius:8px;border-left:4px solid #f44336;">
                        <strong>Remarque :</strong>
                        <p style="margin:8px 0 0;">{{ $rapport->admin_notes }}</p>
                    </div>
                    <div style="margin-top:15px;text-align:center;">
                        <a href="{{ route('technicien.rapports.edit', ['mission' => $mission, 'rapport' => $rapport]) }}" class="btn btn-primary btn-lg">
                            <i class="las la-edit"></i> Modifier mon rapport
                        </a>
                    </div>
                </div>
            </div>
            @endif
        @endif

        {{-- === ACTIONS === --}}
        @if(!in_array($mission->statut, ['terminee', 'soumis']))
        <div class="card mb-3">
            <div class="card-header"><h3><i class="las la-bolt"></i> Actions</h3></div>
            <div class="card-body">
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    @if($mission->statut === 'en_attente')
                        @if($isChef && !$hasAccepted)
                        <form action="{{ route('technicien.missions.accept', $mission) }}" method="POST">@csrf @method('PATCH')<button class="btn btn-success"><i class="las la-check"></i> Accepter la mission</button></form>
                        @elseif(!$isChef)
                        <p class="text-muted"><i class="las la-hourglass"></i> En attente d'acceptation par le chef d'équipe.</p>
                        @endif
                    @elseif($mission->statut === 'a_modifier')
                        {{-- Déjà géré au-dessus --}}
                    @else
                        @if($isChef || $isSolo)
                            @if(!$mission->work_finished_at)
                                @if($mission->statut !== 'en_cours')<form action="{{ route('technicien.missions.statut', $mission) }}" method="POST">@csrf @method('PATCH')<button type="submit" name="statut" value="en_cours" class="btn btn-info btn-sm"><i class="las la-play"></i> Commencer / Reprendre</button></form>@endif
                                @if($mission->statut !== 'en_pause')<form action="{{ route('technicien.missions.statut', $mission) }}" method="POST">@csrf @method('PATCH')<button type="submit" name="statut" value="en_pause" class="btn btn-warning btn-sm"><i class="las la-pause"></i> Suspendre</button></form>@endif
                                <form action="{{ route('technicien.missions.statut', $mission) }}" method="POST">@csrf @method('PATCH')<button type="submit" name="statut" value="terminee" class="btn btn-success btn-sm" onclick="return confirm('Confirmer la fin du travail physique ? Vous devrez rédiger un rapport ensuite.')"><i class="las la-check-double"></i> Travail Terminé</button></form>
                            @else
                                {{-- Travail terminé, en attente du rapport --}}
                                <div style="text-align:center;width:100%;">
                                    <p style="font-size:1.1rem;margin-bottom:16px;"><i class="las la-file-signature" style="font-size:1.5rem;"></i> Travail physique terminé — Rédigez votre rapport</p>
                                    <a href="{{ route('technicien.rapports.create', $mission) }}" class="btn btn-primary btn-lg"><i class="las la-paper-plane"></i> Rédiger et soumettre le rapport</a>
                                </div>
                            @endif
                        @else
                            <p class="text-muted"><i class="las la-eye"></i> Vous êtes membre de cette équipe. Seul le chef contrôle l'avancement.</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- === STATUT SOUMIS : en attente de validation === --}}
        @if($mission->statut === 'soumis')
        <div class="card mb-3" style="border:2px solid #9c27b0;">
            <div class="card-body text-center" style="padding:30px;">
                <i class="las la-hourglass-half" style="font-size:3rem;color:#9c27b0;"></i>
                <p style="font-size:1.15rem;font-weight:600;margin:15px 0 8px;">Rapport soumis — En attente de validation</p>
                <p class="text-muted">L'administration va examiner votre rapport et le valider ou demander des corrections.</p>
            </div>
        </div>
        @endif

        {{-- === INFORMATIONS === --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="las la-info-circle"></i> Informations</h3>
                <span class="badge {{ $mission->statut_class }}" style="font-size:0.85rem;padding:6px 16px;">
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
            </div>
            <div class="card-body">
                <div class="details-grid">
                    <div class="detail-section"><h4>Type</h4><div class="detail-value">{{ $mission->type_mission }}</div></div>
                    <div class="detail-section"><h4>Date</h4><div class="detail-value">{{ $mission->date_mission->format('d/m/Y') }}</div></div>
                    <div class="detail-section"><h4>Adresse</h4><div class="detail-value"><i class="las la-map-marker"></i> {{ $mission->adresse }}</div></div>
                </div>
                <div class="detail-section"><h4>Description</h4><div class="detail-value">{{ $mission->description }}</div></div>
            </div>
        </div>

        {{-- === TIMELINE V13 === --}}
        <div class="card mb-3">
            <div class="card-header"><h3><i class="las la-stream"></i> Chronologie</h3></div>
            <div class="card-body" style="padding:20px;">
                <div class="timeline-v13">
                    <div class="timeline-step {{ $mission->started_at ? 'done' : '' }}">
                        <div class="timeline-dot"><i class="las la-play"></i></div>
                        <div class="timeline-info"><strong>Début</strong><span>{{ $mission->started_at ? $mission->started_at->format('d/m/Y à H:i') : '—' }}</span></div>
                    </div>
                    <div class="timeline-step {{ $mission->work_finished_at ? 'done' : '' }}">
                        <div class="timeline-dot"><i class="las la-flag-checkered"></i></div>
                        <div class="timeline-info"><strong>Fin technique</strong><span>{{ $mission->work_finished_at ? $mission->work_finished_at->format('d/m/Y à H:i') : '—' }}</span></div>
                    </div>
                    <div class="timeline-step {{ $mission->submitted_at ? 'done' : '' }}">
                        <div class="timeline-dot"><i class="las la-paper-plane"></i></div>
                        <div class="timeline-info"><strong>Rapport soumis</strong><span>{{ $mission->submitted_at ? $mission->submitted_at->format('d/m/Y à H:i') : '—' }}</span></div>
                    </div>
                    <div class="timeline-step {{ $mission->validated_at ? 'done' : '' }}">
                        <div class="timeline-dot"><i class="las la-check-double"></i></div>
                        <div class="timeline-info"><strong>Validé</strong><span>{{ $mission->validated_at ? $mission->validated_at->format('d/m/Y à H:i') : '—' }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- === RAPPORT (si soumis ou terminé) === --}}
        @if($rapport && in_array($mission->statut, ['soumis', 'terminee', 'a_modifier']))
        <div class="card mb-3">
            <div class="card-header"><h3><i class="las la-file-alt"></i> Rapport de Mission</h3></div>
            <div class="card-body">
                <div class="detail-section"><h4>Déroulement</h4><div class="detail-value">{{ $rapport->deroulement }}</div></div>
                @if($rapport->difficultes)<div class="detail-section"><h4>Difficultés</h4><div class="detail-value">{{ $rapport->difficultes }}</div></div>@endif
                <div class="detail-section"><h4>Actions réalisées</h4><div class="detail-value">{{ $rapport->actions_realisees }}</div></div>
                @if($rapport->fiche_passage_path)
                <div class="detail-section" style="margin-top:15px;">
                    <h4><i class="las la-file-signature"></i> Fiche de Passage</h4>
                    @if(str_contains($rapport->fiche_passage_path, '.pdf'))
                        <a href="{{ asset('storage/'.$rapport->fiche_passage_path) }}" target="_blank" class="btn btn-secondary btn-sm"><i class="las la-file-pdf"></i> Voir le PDF</a>
                    @else
                        <a href="{{ asset('storage/'.$rapport->fiche_passage_path) }}" target="_blank"><img src="{{ asset('storage/'.$rapport->fiche_passage_path) }}" style="max-width:100%;max-height:300px;border-radius:8px;margin-top:10px;" alt="Fiche"></a>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
    <div>
        {{-- === ÉQUIPE === --}}
        <div class="card">
            <div class="card-header"><h3><i class="las la-users"></i> Équipe</h3><span class="badge badge-info">{{ $mission->is_groupe?'Groupe':'Solo' }}</span></div>
            <div class="card-body" style="padding:12px;">
                <ul class="team-list">
                    @foreach($mission->techniciens as $tech)
                    <li class="team-member">
                        <div class="member-avatar">{{ strtoupper(substr($tech->name, 0, 2)) }}</div>
                        <div style="flex:1;"><div style="font-weight:600;font-size:0.88rem;">{{ $tech->name }} @if($tech->id===$user->id)<span class="text-muted">(vous)</span>@endif @if($tech->pivot->is_chef_equipe)<span class="chef-badge"><i class="las la-crown"></i> Chef</span>@endif</div><div style="font-size:0.75rem;color:var(--text-muted);">{{ $tech->telephone ?? $tech->email }}</div></div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
