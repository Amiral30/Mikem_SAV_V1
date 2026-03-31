@extends('layouts.technicien')

@section('title', 'Rapport de Mission')
@section('page-title', 'Rédiger un Rapport')
@section('page-subtitle', $mission->titre)

@section('content')
<div style="max-width: 800px;">
    <div class="card">
        <div class="card-header">
            <h3>📝 Rapport de Mission</h3>
            <span class="badge {{ $mission->statut_class }}">{{ $mission->statut_label }}</span>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0; padding-left:20px;">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div style="background:var(--bg-input); padding:16px; border-radius:var(--radius-sm); margin-bottom:24px; border-left:3px solid var(--accent-primary);">
                <strong>Mission :</strong> {{ $mission->titre }}<br>
                <span class="text-muted">{{ $mission->type_mission }} · {{ $mission->date_mission->format('d/m/Y') }} · {{ $mission->adresse }}</span>
            </div>

            <form action="{{ route('technicien.rapports.store', $mission) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="deroulement">Déroulement de la mission *</label>
                    <textarea id="deroulement" name="deroulement" class="form-control" rows="5" placeholder="Décrivez comment s'est déroulée la mission..." required>{{ old('deroulement') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="difficultes">Difficultés rencontrées</label>
                    <textarea id="difficultes" name="difficultes" class="form-control" rows="3" placeholder="Avez-vous rencontré des difficultés ? (optionnel)">{{ old('difficultes') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="actions_realisees">Actions réalisées *</label>
                    <textarea id="actions_realisees" name="actions_realisees" class="form-control" rows="5" placeholder="Détaillez les actions effectuées..." required>{{ old('actions_realisees') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Fichiers joints (images, documents)</label>
                    <div class="file-upload-area" onclick="document.getElementById('fileInput').click()">
                        <div class="upload-icon">📁</div>
                        <p>Cliquez pour sélectionner des fichiers</p>
                        <p class="text-muted" style="font-size:0.75rem;">JPG, PNG, PDF, DOC, XLS — Max 10 Mo par fichier</p>
                    </div>
                    <input type="file" id="fileInput" name="fichiers[]" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx" style="display:none;" onchange="showFileList(this)">
                    <div id="fileList" class="file-list mt-1"></div>
                </div>

                <div class="btn-group mt-3">
                    <button type="submit" class="btn btn-primary btn-lg">Soumettre le rapport</button>
                    <a href="{{ route('technicien.missions.show', $mission) }}" class="btn btn-secondary btn-lg">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showFileList(input) {
    const list = document.getElementById('fileList');
    list.innerHTML = '';
    for (const file of input.files) {
        const div = document.createElement('div');
        div.className = 'file-item';
        div.innerHTML = `<span>📎</span><span style="flex:1">${file.name}</span><span class="text-muted" style="font-size:0.75rem">${(file.size / 1024 / 1024).toFixed(2)} Mo</span>`;
        list.appendChild(div);
    }
}
</script>
@endsection
