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
            <div class="detail-section">
                <h4>Pièces jointes</h4>
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
            </div>
            @endif
            <div class="mt-3"><a href="{{ route('technicien.missions.show', $mission) }}" class="btn btn-secondary">← Retour</a></div>
        </div>
    </div>
</div>
@endsection
