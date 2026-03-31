@extends('layouts.admin')

@section('title', 'Modifier Mission')
@section('page-title', 'Modifier la Mission')
@section('page-subtitle', $mission->titre)

@section('content')
<div style="max-width: 800px;">
    <div class="card">
        <div class="card-header">
            <h3>✏️ Modifier la mission</h3>
            <span class="badge {{ $mission->statut_class }}">{{ $mission->statut_label }}</span>
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

            <form action="{{ route('admin.missions.update', $mission) }}" method="POST">
                @csrf @method('PUT')

                <div class="form-group">
                    <label for="titre">Titre *</label>
                    <input type="text" id="titre" name="titre" class="form-control" value="{{ old('titre', $mission->titre) }}" required>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label for="type_mission">Type *</label>
                        <select id="type_mission" name="type_mission" class="form-control" required>
                            @foreach(['Installation','Réparation','Maintenance','Diagnostic','Dépannage','Autre'] as $type)
                                <option value="{{ $type }}" {{ old('type_mission', $mission->type_mission) == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_mission">Date *</label>
                        <input type="date" id="date_mission" name="date_mission" class="form-control" value="{{ old('date_mission', $mission->date_mission->format('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="adresse">Adresse *</label>
                    <input type="text" id="adresse" name="adresse" class="form-control" value="{{ old('adresse', $mission->adresse) }}" required>
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" class="form-control" rows="4" required>{{ old('description', $mission->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="prix_deplacement">Prix déplacement (€)</label>
                    <input type="number" id="prix_deplacement" name="prix_deplacement" class="form-control" step="0.01" min="0" value="{{ old('prix_deplacement', $mission->prix_deplacement) }}">
                </div>

                <hr style="border-color: var(--border-color); margin: 30px 0;">
                <h3 style="margin-bottom:20px;">👨‍🔧 Assignation</h3>

                @php $assignedIds = $mission->techniciens->pluck('id')->toArray(); @endphp

                <div class="form-group">
                    <label>Techniciens *</label>
                    <div style="max-height:250px; overflow-y:auto; background:var(--bg-input); border:1px solid var(--border-color); border-radius:var(--radius-sm); padding:12px;">
                        @foreach($techniciens as $tech)
                        <div class="form-check">
                            <input type="checkbox" name="techniciens[]" value="{{ $tech->id }}" id="tech_{{ $tech->id }}"
                                   {{ in_array($tech->id, old('techniciens', $assignedIds)) ? 'checked' : '' }}
                                   onchange="updateChefOptions()">
                            <label for="tech_{{ $tech->id }}" style="margin-bottom:0; cursor:pointer; flex:1;">
                                {{ $tech->name }}
                                <span class="badge {{ $tech->disponible ? 'badge-disponible' : 'badge-occupe' }}" style="font-size:0.65rem;">
                                    {{ $tech->disponible ? 'Dispo' : 'Occupé' }}
                                </span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group" id="chefEquipeGroup">
                    <label for="chef_equipe_id">Chef d'équipe</label>
                    <select id="chef_equipe_id" name="chef_equipe_id" class="form-control">
                        <option value="">Sélectionner...</option>
                    </select>
                </div>

                <div class="btn-group mt-3">
                    <button type="submit" class="btn btn-primary btn-lg">Mettre à jour</button>
                    <a href="{{ route('admin.missions.show', $mission) }}" class="btn btn-secondary btn-lg">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const techniciens = @json($techniciens);
const currentChefId = {{ $mission->chef_equipe_id ?? 'null' }};

function updateChefOptions() {
    const checked = document.querySelectorAll('input[name="techniciens[]"]:checked');
    const chefGroup = document.getElementById('chefEquipeGroup');
    const chefSelect = document.getElementById('chef_equipe_id');

    if (checked.length > 1) {
        chefGroup.style.display = 'block';
        chefSelect.innerHTML = '<option value="">Sélectionner...</option>';
        checked.forEach(cb => {
            const tech = techniciens.find(t => t.id == cb.value);
            if (tech) {
                const selected = tech.id == currentChefId ? 'selected' : '';
                chefSelect.innerHTML += `<option value="${tech.id}" ${selected}>${tech.name}</option>`;
            }
        });
    } else {
        chefGroup.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', updateChefOptions);
</script>
@endsection
