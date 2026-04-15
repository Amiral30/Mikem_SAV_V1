@extends('layouts.admin')
@section('title', 'Missions')
@section('page-title', 'Gestion des Missions')
@section('page-subtitle', 'Liste de toutes les missions')
@section('topbar-actions')
<a href="{{ route('admin.missions.create') }}" class="btn btn-primary btn-sm">+ Nouvelle Mission</a>
@endsection

@section('content')
<div class="filter-bar">
    <form action="{{ route('admin.missions.index') }}" method="GET" style="display:flex;gap:12px;flex-wrap:wrap;width:100%;">
        <div class="search-input"><span class="search-icon"><i class="las la-search"></i></span><input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}"></div>
        <select name="statut" class="form-control" onchange="this.form.submit()">
            <option value="">Tous les statuts</option>
            @foreach(\App\Models\Mission::STATUTS as $key => $label)<option value="{{ $key }}" {{ request('statut')==$key?'selected':'' }}>{{ $label }}</option>@endforeach
        </select>
        <button type="submit" class="btn btn-secondary btn-sm">Filtrer</button>
        @if(request()->hasAny(['search','statut']))<a href="{{ route('admin.missions.index') }}" class="btn btn-secondary btn-sm">Réinitialiser</a>@endif
    </form>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>#</th><th>Titre</th><th>Type</th><th>Adresse</th><th>Date</th><th>Statut</th><th>Équipe</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($missions as $mission)
                <tr data-href="{{ route('admin.missions.show', $mission) }}">
                    <td data-label="#">{{ $mission->id }}</td>
                    <td data-label="Titre"><strong>{{ Str::limit($mission->titre, 35) }}</strong></td>
                    <td data-label="Type">{{ $mission->type_mission }}</td>
                    <td data-label="Adresse" style="max-width:200px;">{{ Str::limit($mission->adresse, 30) }}</td>
                    <td data-label="Date">{{ $mission->date_mission->format('d/m/Y') }}</td>
                    <td data-label="Statut">
                        <span class="badge {{ $mission->statut_class }}">
                            @switch($mission->statut)
                                @case('en_attente') <i class="las la-clock"></i> @break
                                @case('en_cours') <i class="las la-tools"></i> @break
                                @case('en_pause') <i class="las la-pause-circle"></i> @break
                                @case('suspendue') <i class="las la-stop-circle"></i> @break
                                @case('soumis') <i class="las la-file-alt"></i> @break
                                @case('a_modifier') <i class="las la-exclamation-triangle"></i> @break
                                @case('terminee') <i class="las la-check-double"></i> @break
                                @default <i class="las la-info-circle"></i>
                            @endswitch
                            {{ $mission->statut_label }}
                        </span>
                    </td>
                    <td data-label="Équipe"><span style="font-size:0.85rem;">{{ $mission->techniciens->count() }} tech.</span> @if($mission->is_groupe)<span class="badge badge-info" style="font-size:0.65rem;"><i class="las la-users"></i> Groupe</span>@endif</td>
                    <td data-label="Actions">
                        <div class="btn-group">
                            <a href="{{ route('admin.missions.show', $mission) }}" class="btn btn-secondary btn-sm"><i class="las la-eye"></i></a>
                            @if($mission->statut === 'en_attente')
                            <a href="{{ route('admin.missions.edit', $mission) }}" class="btn btn-secondary btn-sm"><i class="las la-pen"></i></a>
                            <form action="{{ route('admin.missions.destroy', $mission) }}" method="POST" onsubmit="return confirm('Confirmer la suppression définitive ?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="las la-trash"></i></button></form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted" style="padding:40px">Aucune mission.<br><a href="{{ route('admin.missions.create') }}" class="btn btn-primary btn-sm mt-2">Créer une mission</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($missions->hasPages())<div class="card-footer">{{ $missions->withQueryString()->links() }}</div>@endif
</div>
@endsection
