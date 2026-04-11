@extends('layouts.admin')
@section('title', 'Nouvelle Mission')
@section('page-title', 'Créer une Mission')

@section('content')
<div style="max-width:800px;">
    <div class="card">
        <div class="card-header"><h3><i class="las la-clipboard-list"></i> Détails de la mission</h3></div>
        <div class="card-body">
            @if($errors->any())<div class="alert alert-danger"><ul style="margin:0;padding-left:20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <form action="{{ route('admin.missions.store') }}" method="POST">
                @csrf
                <div class="form-group"><label for="titre">Titre *</label><input type="text" id="titre" name="titre" class="form-control" value="{{ old('titre') }}" placeholder="Ex: Réparation climatisation" required></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group"><label for="type_mission">Type *</label>
                        <select id="type_mission" name="type_mission" class="form-control" required>
                            <option value="">Sélectionner...</option>
                            @foreach(['Installation','Réparation','Maintenance','Diagnostic','Dépannage','Autre'] as $type)
                            <option value="{{ $type }}" {{ old('type_mission')==$type?'selected':'' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group"><label for="date_mission">Date *</label><input type="date" id="date_mission" name="date_mission" class="form-control" value="{{ old('date_mission') }}" required></div>
                </div>
                <div class="form-group"><label for="adresse">Adresse *</label><input type="text" id="adresse" name="adresse" class="form-control" value="{{ old('adresse') }}" placeholder="Adresse complète" required></div>
                <div class="form-group"><label for="description">Description *</label><textarea id="description" name="description" class="form-control" rows="4" placeholder="Décrivez la mission..." required>{{ old('description') }}</textarea></div>
                <div class="form-group"><label for="prix_deplacement">Prix déplacement (Fcfa)</label><input type="number" id="prix_deplacement" name="prix_deplacement" class="form-control" step="50" min="0" value="{{ old('prix_deplacement') }}" placeholder="Fcfa"></div>

                <hr style="border-color:var(--border-color);margin:30px 0;">
                <h3 style="margin-bottom:20px;"><i class="las la-user-cog"></i> Assignation</h3>
                <div class="form-group">
                    <label>Techniciens *</label>
                    <div style="max-height:250px;overflow-y:auto;background:var(--bg-input);border:1px solid var(--border-color);border-radius:var(--radius-sm);padding:12px;">
                        @forelse($techniciens as $tech)
                        <div class="form-check">
                            <input type="checkbox" name="techniciens[]" value="{{ $tech->id }}" id="tech_{{ $tech->id }}" {{ in_array($tech->id, old('techniciens', []))?'checked':'' }} onchange="updateChefOptions()">
                            <label for="tech_{{ $tech->id }}" style="margin-bottom:0;cursor:pointer;flex:1;">{{ $tech->name }} <span class="badge {{ $tech->disponible?'badge-disponible':'badge-occupe' }}" style="font-size:0.65rem;">{{ $tech->disponible?'Dispo':'Occupé' }}</span></label>
                        </div>
                        @empty
                        <p class="text-muted">Aucun technicien. <a href="{{ route('admin.techniciens.create') }}">En créer un</a></p>
                        @endforelse
                    </div>
                </div>
                <div class="form-group" id="chefEquipeGroup" style="display:none;">
                    <label for="chef_equipe_id">Chef d'équipe</label>
                    <select id="chef_equipe_id" name="chef_equipe_id" class="form-control"><option value="">Sélectionner...</option></select>
                    <small class="text-muted">Le chef d'équipe doit accepter la mission</small>
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
        chefSelect.innerHTML = '<option value="">Sélectionner...</option>';
        checked.forEach(cb => { const t = techniciens.find(x => x.id == cb.value); if(t) chefSelect.innerHTML += `<option value="${t.id}">${t.name}</option>`; });
    } else { chefGroup.style.display = 'none'; }
}
document.addEventListener('DOMContentLoaded', updateChefOptions);

document.querySelector('form').addEventListener('submit', function(e) {
    const btn = this.querySelector('button[type="submit"]');
    if (btn.disabled) return e.preventDefault();
    btn.disabled = true;
    btn.innerHTML = '<i class="las la-spinner la-spin"></i> Création en cours...';
});
</script>
@endsection
