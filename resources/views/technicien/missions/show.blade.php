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
            <div class="card-header"><h3>⚡ Actions</h3></div>
            <div class="card-body">
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    @if($isChef && !$hasAccepted && $mission->statut === 'en_attente')
                    <form action="{{ route('technicien.missions.accept', $mission) }}" method="POST">@csrf @method('PATCH')<button class="btn btn-success">✅ Accepter la mission</button></form>
                    @endif
                    @if($mission->statut !== 'en_attente' || ($isChef && $hasAccepted) || $isSolo)
                        @if($mission->statut !== 'en_cours')<form action="{{ route('technicien.missions.statut', $mission) }}" method="POST">@csrf @method('PATCH')<button type="submit" name="statut" value="en_cours" class="btn btn-info btn-sm">▶️ En cours</button></form>@endif
                        @if($mission->statut !== 'en_pause')<form action="{{ route('technicien.missions.statut', $mission) }}" method="POST">@csrf @method('PATCH')<button type="submit" name="statut" value="en_pause" class="btn btn-warning btn-sm">⏸️ Pause</button></form>@endif
                        <form action="{{ route('technicien.missions.statut', $mission) }}" method="POST">@csrf @method('PATCH')<button type="submit" name="statut" value="terminee" class="btn btn-success btn-sm" onclick="return confirm('Terminer ?')">✅ Terminée</button></form>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div class="card mb-3">
            <div class="card-header"><h3>📋 Informations</h3><span class="badge {{ $mission->statut_class }}" style="font-size:0.85rem;padding:6px 16px;">{{ $mission->statut_label }}</span></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div class="detail-section"><h4>Type</h4><div class="detail-value">{{ $mission->type_mission }}</div></div>
                    <div class="detail-section"><h4>Date</h4><div class="detail-value">{{ $mission->date_mission->format('d/m/Y') }}</div></div>
                    <div class="detail-section"><h4>Adresse</h4><div class="detail-value">📍 {{ $mission->adresse }}</div></div>
                    @if($mission->prix_deplacement)<div class="detail-section"><h4>Prix déplacement</h4><div class="detail-value">{{ number_format($mission->prix_deplacement, 2, ',', ' ') }} €</div></div>@endif
                </div>
                <div class="detail-section"><h4>Description</h4><div class="detail-value">{{ $mission->description }}</div></div>
            </div>
        </div>

        @if($mission->statut === 'terminee')
            @php $myRapport = $mission->rapports->where('user_id', $user->id)->first(); @endphp
            @if($myRapport)
            <div class="card mb-3">
                <div class="card-header"><h3>📝 Mon Rapport</h3></div>
                <div class="card-body">
                    <div class="detail-section"><h4>Déroulement</h4><div class="detail-value">{{ $myRapport->deroulement }}</div></div>
                    @if($myRapport->difficultes)<div class="detail-section"><h4>Difficultés</h4><div class="detail-value">{{ $myRapport->difficultes }}</div></div>@endif
                    <div class="detail-section"><h4>Actions réalisées</h4><div class="detail-value">{{ $myRapport->actions_realisees }}</div></div>
                    @if($myRapport->fichiers)<div class="file-list">@foreach($myRapport->fichiers as $f)<div class="file-item"><span>📎</span><a href="{{ asset('storage/'.$f['path']) }}" target="_blank">{{ $f['name'] }}</a></div>@endforeach</div>@endif
                </div>
            </div>
            @else
            <div class="card mb-3"><div class="card-body text-center" style="padding:30px;"><p style="font-size:1.1rem;margin-bottom:16px;">📝 Mission terminée — Rédigez votre rapport</p><a href="{{ route('technicien.rapports.create', $mission) }}" class="btn btn-primary btn-lg">Rédiger le rapport</a></div></div>
            @endif
        @endif
    </div>
    <div>
        <div class="card">
            <div class="card-header"><h3>👨‍🔧 Équipe</h3><span class="badge badge-info">{{ $mission->is_groupe?'Groupe':'Solo' }}</span></div>
            <div class="card-body" style="padding:12px;">
                <ul class="team-list">
                    @foreach($mission->techniciens as $tech)
                    <li class="team-member">
                        <div class="member-avatar">{{ strtoupper(substr($tech->name, 0, 2)) }}</div>
                        <div style="flex:1;"><div style="font-weight:600;font-size:0.88rem;">{{ $tech->name }} @if($tech->id===$user->id)<span class="text-muted">(vous)</span>@endif @if($tech->pivot->is_chef_equipe)<span class="chef-badge">👑 Chef</span>@endif</div><div style="font-size:0.75rem;color:var(--text-muted);">{{ $tech->telephone ?? $tech->email }}</div></div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
