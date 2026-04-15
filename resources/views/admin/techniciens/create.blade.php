@extends('layouts.admin')
@section('title', 'Ajouter Technicien')
@section('page-title', 'Ajouter un Technicien')

@section('content')
<div style="max-width:600px;">
    <div class="card">
        <div class="card-header"><h3>👤 Nouveau technicien</h3></div>
        <div class="card-body">
            @if($errors->any())<div class="alert alert-danger"><ul style="margin:0;padding-left:20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <form action="{{ route('admin.techniciens.store') }}" method="POST">
                @csrf
                <div class="form-group"><label for="name">Nom complet *</label><input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required></div>
                <div class="form-group"><label for="email">Email *</label><input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
                <div class="form-group"><label for="telephone">Téléphone</label><input type="text" id="telephone" name="telephone" class="form-control" value="{{ old('telephone') }}"></div>
                
                <div style="background: var(--bg-primary); padding: 15px; border-radius: 8px; border: 1px solid var(--border-color); margin: 20px 0;">
                    <p style="margin: 0; font-size: 0.85rem; color: var(--text-muted);">
                        <i class="las la-info-circle" style="color: var(--accent-primary); font-size: 1.1rem; vertical-align: middle;"></i> 
                        Le mot de passe par défaut est : <strong>tech123</strong>. <br>
                        Le technicien devra obligatoirement le changer lors de sa première connexion.
                    </p>
                </div>

                <div class="btn-group mt-3"><button type="submit" class="btn btn-primary btn-lg">Créer le technicien</button><a href="{{ route('admin.techniciens.index') }}" class="btn btn-secondary btn-lg">Annuler</a></div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const btn = this.querySelector('button[type="submit"]');
    if (btn.disabled) return e.preventDefault();
    btn.disabled = true;
    btn.innerHTML = '<i class="las la-spinner la-spin"></i> Création...';
});
</script>
@endsection
