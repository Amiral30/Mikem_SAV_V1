@extends('layouts.admin')
@section('title', 'Modifier Technicien')
@section('page-title', 'Modifier le Technicien')
@section('page-subtitle', $technicien->name)

@section('content')
<div style="max-width:600px;">
    <div class="card">
        <div class="card-header"><h3>✏️ Modifier</h3></div>
        <div class="card-body">
            @if($errors->any())<div class="alert alert-danger"><ul style="margin:0;padding-left:20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <form action="{{ route('admin.techniciens.update', $technicien) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-group"><label for="name">Nom *</label><input type="text" id="name" name="name" class="form-control" value="{{ old('name', $technicien->name) }}" required></div>
                <div class="form-group"><label for="email">Email *</label><input type="email" id="email" name="email" class="form-control" value="{{ old('email', $technicien->email) }}" required></div>
                <div class="form-group"><label for="telephone">Téléphone</label><input type="text" id="telephone" name="telephone" class="form-control" value="{{ old('telephone', $technicien->telephone) }}"></div>
                <div class="form-group"><label for="password">Nouveau mot de passe (optionnel)</label><input type="password" id="password" name="password" class="form-control"></div>
                <div class="form-group"><label for="password_confirmation">Confirmer</label><input type="password" id="password_confirmation" name="password_confirmation" class="form-control"></div>
                <div class="btn-group mt-3"><button type="submit" class="btn btn-primary btn-lg">Mettre à jour</button><a href="{{ route('admin.techniciens.show', $technicien) }}" class="btn btn-secondary btn-lg">Annuler</a></div>
            </form>
        </div>
    </div>
</div>
@endsection
