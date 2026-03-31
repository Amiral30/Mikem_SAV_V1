@extends('layouts.technicien')
@section('title', 'Rapport')
@section('page-title', 'Rédiger un Rapport')
@section('page-subtitle', $mission->titre)

@section('content')
<div style="max-width:800px;">
    <div class="card">
        <div class="card-header"><h3>📝 Rapport de Mission</h3></div>
        <div class="card-body">
            @if($errors->any())<div class="alert alert-danger"><ul style="margin:0;padding-left:20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <div style="background:var(--bg-input);padding:16px;border-radius:var(--radius-sm);margin-bottom:24px;border-left:3px solid var(--accent-primary);">
                <strong>Mission :</strong> {{ $mission->titre }}<br><span class="text-muted">{{ $mission->type_mission }} · {{ $mission->date_mission->format('d/m/Y') }} · {{ $mission->adresse }}</span>
            </div>
            <form action="{{ route('technicien.rapports.store', $mission) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group"><label for="deroulement">Déroulement *</label><textarea id="deroulement" name="deroulement" class="form-control" rows="5" placeholder="Comment s'est déroulée la mission..." required>{{ old('deroulement') }}</textarea></div>
                <div class="form-group"><label for="difficultes">Difficultés (optionnel)</label><textarea id="difficultes" name="difficultes" class="form-control" rows="3" placeholder="Difficultés rencontrées...">{{ old('difficultes') }}</textarea></div>
                <div class="form-group"><label for="actions_realisees">Actions réalisées *</label><textarea id="actions_realisees" name="actions_realisees" class="form-control" rows="5" placeholder="Actions effectuées..." required>{{ old('actions_realisees') }}</textarea></div>
                <div class="form-group">
                    <label>Fichiers joints</label>
                    <div class="file-upload-area" onclick="document.getElementById('fileInput').click()">
                        <div class="upload-icon">📁</div><p>Cliquez pour sélectionner</p><p class="text-muted" style="font-size:0.75rem;">JPG, PNG, PDF, DOC, XLS — Max 10 Mo</p>
                    </div>
                    <input type="file" id="fileInput" name="fichiers[]" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx" style="display:none;" onchange="showFiles(this)">
                    <div id="fileList" class="file-list mt-1"></div>
                </div>
                <div class="btn-group mt-3"><button type="submit" class="btn btn-primary btn-lg">Soumettre</button><a href="{{ route('technicien.missions.show', $mission) }}" class="btn btn-secondary btn-lg">Annuler</a></div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
function showFiles(input) {
    const list = document.getElementById('fileList'); list.innerHTML = '';
    for (const f of input.files) { const d = document.createElement('div'); d.className='file-item'; d.innerHTML=`<span>📎</span><span style="flex:1">${f.name}</span><span class="text-muted" style="font-size:0.75rem">${(f.size/1024/1024).toFixed(2)} Mo</span>`; list.appendChild(d); }
}
</script>
@endsection
