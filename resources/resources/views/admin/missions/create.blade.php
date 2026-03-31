@extends('layouts.admin')

@section('title', 'Nouvelle Mission')
@section('page-title', 'Créer une Mission')
@section('page-subtitle', 'Formulaire de création d\'une nouvelle mission')

@section('content')
<div style="max-width: 800px;">
    <div class="card">
        <div class="card-header">
            <h3>📋 Détails de la mission</h3>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0; padding-left:20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.missions.store') }}" method="POST" id="missionForm">
                @csrf

                <div class="form-group">
                    <label for="titre">Titre de la mission *</label>
                    <input type="text" id="titre" name="titre" class="form-control" value="{{ old('titre') }}" placeholder="Ex: Réparation climatisation" required>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label for="type_mission">Type de mission *</label>
                        <select id="type_mission" name="type_mission" class="form-control" required>
                            <option value="">Sélectionner...</option>
                            <option value="Installation" {{ old('type_mission') == 'Installation' ? 'selected' : '' }}>Installation</option>
                            <option value="Réparation" {{ old('type_mission') == 'Réparation' ? 'selected' : '' }}>Réparation</option>
                            <option value="Maintenance" {{ old('type_mission') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="Diagnostic" {{ old('type_mission') == 'Diagnostic' ? 'selected' : '' }}>Diagnostic</option>
                            <option value="Dépannage" {{ old('type_mission') == 'Dépannage' ? 'selected' : '' }}>Dépannage</option>
                            <option value="Autre" {{ old('type_mission') == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_mission">Date de la mission *</label>
                        <input type="date" id="date_mission" name="date_mission" class="form-control" value="{{ old('date_mission') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="adresse">Adresse *</label>
                    <input type="text" id="adresse" name="adresse" class="form-control" value="{{ old('adresse') }}" placeholder="Adresse complète du lieu d'intervention" required>
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Décrivez la mission en détail..." required>{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="prix_deplacement">Prix de déplacement (€) - optionnel</label>
                    <input type="number" id="prix_deplacement" name="prix_deplacement" class="form-control" step="0.01" min="0" value="{{ old('prix_deplacement') }}" placeholder="0.00">
                </div>

                <hr style="border-color: var(--border-color); margin: 30px 0;">

                <h3 style="margin-bottom:20px;">👨‍🔧 Assignation des techniciens</h3>

                <div class="form-group">
                    <label>Sélectionner les techniciens *</label>
                    <div style="max-height:250px; overflow-y:auto; background:var(--bg-input); border:1px solid var(--border-color); border-radius:var(--radius-sm); padding:12px;">
                        @forelse($techniciens as $tech)
                        <div class="form-check">
                            <input type="checkbox" name="techniciens[]" value="{{ $tech->id }}" id="tech_{{ $tech->id }}"
                                   {{ in_array($tech->id, old('techniciens', [])) ? 'checked' : '' }}
                                   onchange="updateChefOptions()">
                            <label for="tech_{{ $tech->id }}" style="margin-bottom:0; cursor:pointer; flex:1;">
                                {{ $tech->name }}
                                <span class="badge {{ $tech->disponible ? 'badge-disponible' : 'badge-occupe' }}" style="font-size:0.65rem;">
                                    {{ $tech->disponible ? 'Disponible' : 'Occupé' }}
                                </span>
                            </label>
                        </div>
                        @empty
                        <p class="text-muted">Aucun technicien disponible. <a href="{{ route('admin.techniciens.create') }}">En créer un</a></p>
                        @endforelse
                    </div>
                </div>

                <div class="form-group" id="chefEquipeGroup" style="display:none;">
                    <label for="chef_equipe_id">Chef d'équipe</label>
                    <select id="chef_equipe_id" name="chef_equipe_id" class="form-control">
                        <option value="">Sélectionner le chef d'équipe...</option>
                    </select>
                    <small class="text-muted">Le chef d'équipe doit accepter la mission pour l'équipe</small>
                </div>

                <div class="btn-group mt-3">
                    <button type="submit" class="btn btn-primary btn-lg">Créer la mission</button>
                    <a href="{{ route('admin.missions.index') }}" class="btn btn-secondary btn-lg">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const techniciens = @json($techniciens);

function updateChefOptions() {
    const checked = document.querySelectorAll('input[name="techniciens[]"]:checked');
    const chefGroup = document.getElementById('chefEquipeGroup');
    const chefSelect = document.getElementById('chef_equipe_id');

    if (checked.length > 1) {
        chefGroup.style.display = 'block';
        chefSelect.innerHTML = '<option value="">Sélectionner le chef d\'équipe...</option>';
        checked.forEach(cb => {
            const tech = techniciens.find(t => t.id == cb.value);
            if (tech) {
                chefSelect.innerHTML += `<option value="${tech.id}">${tech.name}</option>`;
            }
        });
    } else {
        chefGroup.style.display = 'none';
        if (checked.length === 1) {
            chefSelect.innerHTML = `<option value="${checked[0].value}" selected>Auto</option>`;
        }
    }
}

document.addEventListener('DOMContentLoaded', updateChefOptions);
</script>
@endsection
