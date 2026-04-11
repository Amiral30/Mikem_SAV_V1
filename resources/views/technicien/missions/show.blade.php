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
@endphp

<div class="mission-detail-grid">
    <div>
        @if($mission->statut !== 'terminee')
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
                    @else
                        @if($isChef || $isSolo)
                            @if($mission->statut !== 'en_cours')<form action="{{ route('technicien.missions.statut', $mission) }}" method="POST">@csrf @method('PATCH')<button type="submit" name="statut" value="en_cours" class="btn btn-info btn-sm"><i class="las la-play"></i> Commencer / Reprendre</button></form>@endif
                            @if($mission->statut !== 'en_pause')<form action="{{ route('technicien.missions.statut', $mission) }}" method="POST">@csrf @method('PATCH')<button type="submit" name="statut" value="en_pause" class="btn btn-warning btn-sm"><i class="las la-pause"></i> Suspendre (Pause)</button></form>@endif
                            <form action="{{ route('technicien.missions.statut', $mission) }}" method="POST">@csrf @method('PATCH')<button type="submit" name="statut" value="terminee" class="btn btn-success btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir terminer cette mission ? Vous devrez rédiger un rapport par la suite.')"><i class="las la-check-double"></i> Terminée</button></form>
                        @else
                            <p class="text-muted"><i class="las la-eye"></i> Vous êtes membre de cette équipe. Seul le chef d'équipe contrôle l'avancement de la mission.</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div class="card mb-3">
            <div class="card-header"><h3><i class="las la-info-circle"></i> Informations</h3><span class="badge {{ $mission->statut_class }}" style="font-size:0.85rem;padding:6px 16px;">{{ $mission->statut_label }}</span></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div class="detail-section"><h4>Type</h4><div class="detail-value">{{ $mission->type_mission }}</div></div>
                    <div class="detail-section"><h4>Date</h4><div class="detail-value">{{ $mission->date_mission->format('d/m/Y') }}</div></div>
                    <div class="detail-section"><h4>Adresse</h4><div class="detail-value"><i class="las la-map-marker"></i> {{ $mission->adresse }}</div></div>
                </div>
                <div class="detail-section"><h4>Description</h4><div class="detail-value">{{ $mission->description }}</div></div>
            </div>
        </div>

        @if($mission->statut === 'terminee')
            @php $rapport = $mission->rapports->first(); @endphp
            @if($rapport)
            <div class="card mb-3">
                <div class="card-header"><h3><i class="las la-file-alt"></i> Rapport de Mission</h3></div>
                <div class="card-body">
                    <div class="detail-section"><h4>Déroulement</h4><div class="detail-value">{{ $rapport->deroulement }}</div></div>
                    @if($rapport->difficultes)<div class="detail-section"><h4>Difficultés</h4><div class="detail-value">{{ $rapport->difficultes }}</div></div>@endif
                    <div class="detail-section"><h4>Actions réalisées</h4><div class="detail-value">{{ $rapport->actions_realisees }}</div></div>
                    @if($rapport->fichiers)
                    <div class="file-list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; margin-top: 15px;">
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
                    @endif
                </div>
            </div>
            @else
                @if($isChef || $isSolo)
                <div class="card mb-3"><div class="card-body text-center" style="padding:30px;"><p style="font-size:1.1rem;margin-bottom:16px;"><i class="las la-file-signature" style="font-size:1.5rem;"></i> Mission terminée — Rédigez le rapport</p><a href="{{ route('technicien.rapports.create', $mission) }}" class="btn btn-primary btn-lg">Rédiger le rapport</a></div></div>
                @else
                <div class="card mb-3"><div class="card-body text-center" style="padding:30px;"><p style="font-size:1.1rem;margin-bottom:0;color:var(--text-muted);"><i class="las la-hourglass-half" style="font-size:1.5rem;"></i> En attente de la rédaction du rapport par le chef d'équipe...</p></div></div>
                @endif
            @endif
        @endif
    </div>
    <div>
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
