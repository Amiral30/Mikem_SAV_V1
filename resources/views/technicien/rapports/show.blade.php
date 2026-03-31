@extends('layouts.technicien')
@section('title', 'Rapport')
@section('page-title', 'Rapport de Mission')

@section('content')
<div style="max-width:800px;">
    <div class="card">
        <div class="card-header"><h3>📝 Rapport #{{ $rapport->id }}</h3><span class="text-muted">{{ $rapport->created_at->format('d/m/Y H:i') }}</span></div>
        <div class="card-body">
            <div style="background:var(--bg-input);padding:16px;border-radius:var(--radius-sm);margin-bottom:24px;border-left:3px solid var(--accent-primary);">
                <strong>Mission :</strong> {{ $rapport->mission->titre }}<br><span class="text-muted">Par {{ $rapport->user->name }}</span>
            </div>
            <div class="detail-section"><h4>Déroulement</h4><div class="detail-value">{{ $rapport->deroulement }}</div></div>
            @if($rapport->difficultes)<div class="detail-section"><h4>Difficultés</h4><div class="detail-value">{{ $rapport->difficultes }}</div></div>@endif
            <div class="detail-section"><h4>Actions réalisées</h4><div class="detail-value">{{ $rapport->actions_realisees }}</div></div>
            @if($rapport->fichiers && count($rapport->fichiers) > 0)
            <div class="detail-section"><h4>Fichiers</h4><div class="file-list">@foreach($rapport->fichiers as $f)<div class="file-item"><span>📎</span><a href="{{ asset('storage/'.$f['path']) }}" target="_blank" style="flex:1;">{{ $f['name'] }}</a><span class="text-muted" style="font-size:0.75rem;">{{ $f['type'] }}</span></div>@endforeach</div></div>
            @endif
            <div class="mt-3"><a href="{{ route('technicien.missions.show', $mission) }}" class="btn btn-secondary">← Retour</a></div>
        </div>
    </div>
</div>
@endsection
