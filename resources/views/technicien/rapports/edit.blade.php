@extends('layouts.technicien')
@section('title', 'Modifier le Rapport')
@section('page-title', 'Modifier le Rapport')
@section('page-subtitle', $mission->titre)

@section('content')
<div style="max-width:850px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <h3><i class="las la-edit"></i> Modifier le Rapport d'Intervention</h3>
        </div>
        <div class="card-body" style="padding: 30px;">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0;padding-left:20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            {{-- Note de l'Admin si présente --}}
            @if($rapport->admin_notes)
            <div class="alert alert-danger" style="border-left: 4px solid #f44336; background: rgba(244,67,54,0.08);">
                <strong><i class="las la-comment-alt"></i> Remarque de l'Administrateur :</strong>
                <p style="margin: 8px 0 0;">{{ $rapport->admin_notes }}</p>
            </div>
            @endif

            <form action="{{ route('technicien.rapports.update', ['mission' => $mission, 'rapport' => $rapport]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="font-weight: 600; margin-bottom: 10px; display: block;">
                        <i class="las la-align-left text-muted"></i> Déroulement de la mission <span class="text-danger">*</span>
                    </label>
                    <textarea name="deroulement" class="form-control" rows="5" required style="resize:vertical;padding:15px;border-radius:8px;">{{ old('deroulement', $rapport->deroulement) }}</textarea>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="font-weight: 600; margin-bottom: 10px; display: block;">
                        <i class="las la-tools text-muted"></i> Actions réalisées <span class="text-danger">*</span>
                    </label>
                    <textarea name="actions_realisees" class="form-control" rows="5" required style="resize:vertical;padding:15px;border-radius:8px;">{{ old('actions_realisees', $rapport->actions_realisees) }}</textarea>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="font-weight: 600; margin-bottom: 10px; display: block;">
                        <i class="las la-exclamation-triangle text-muted"></i> Difficultés (optionnel)
                    </label>
                    <textarea name="difficultes" class="form-control" rows="3" style="resize:vertical;padding:15px;border-radius:8px;">{{ old('difficultes', $rapport->difficultes) }}</textarea>
                </div>

                {{-- Fiche de passage --}}
                <div class="form-group" style="margin-bottom: 25px; border: 2px solid var(--accent-primary); border-radius: 12px; padding: 20px;">
                    <label style="font-weight: 700; color: var(--accent-primary); display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                        <i class="las la-file-signature" style="font-size:1.4rem;"></i> Fiche de Passage Signée <span class="text-danger">*</span>
                    </label>
                    @if($rapport->fiche_passage_path)
                    <div style="margin-bottom:12px; padding:10px; background:var(--bg-secondary);border-radius:8px;">
                        <i class="las la-check-circle text-success"></i> Fiche actuelle :
                        <a href="{{ asset('storage/'.$rapport->fiche_passage_path) }}" target="_blank" style="font-weight:600;">Voir la fiche</a>
                        <small class="text-muted"> — Vous pouvez la remplacer ci-dessous</small>
                    </div>
                    @endif
                    <input type="file" name="fiche_passage" class="form-control" accept=".jpg,.jpeg,.png,.pdf" {{ !$rapport->fiche_passage_path ? 'required' : '' }}>
                    <small class="text-muted">JPG, PNG ou PDF — 2 Mo max</small>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:15px;">
                    <a href="{{ route('technicien.missions.show', $mission) }}" class="btn btn-secondary btn-lg">Annuler</a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="las la-paper-plane"></i> Soumettre à nouveau
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
