@extends('layouts.admin')
@section('title', 'Techniciens')
@section('page-title', 'Gestion des Techniciens')
@section('topbar-actions')<a href="{{ route('admin.techniciens.create') }}" class="btn btn-primary btn-sm">+ Ajouter</a>@endsection

@section('content')
<div class="filter-bar">
    <form action="{{ route('admin.techniciens.index') }}" method="GET" style="display:flex;gap:12px;flex-wrap:wrap;width:100%;">
        <div class="search-input"><span class="search-icon"><i class="las la-search"></i></span><input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}"></div>
        <select name="disponibilite" class="form-control" onchange="this.form.submit()">
            <option value="">Toutes</option>
            <option value="disponible" {{ request('disponibilite')=='disponible'?'selected':'' }}>Disponible</option>
            <option value="occupe" {{ request('disponibilite')=='occupe'?'selected':'' }}>Occupé</option>
        </select>
        <button type="submit" class="btn btn-secondary btn-sm">Filtrer</button>
    </form>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>Technicien</th><th>Email</th><th>Téléphone</th><th>Missions</th><th>Disponibilité</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($techniciens as $tech)
                <tr data-href="{{ route('admin.techniciens.show', $tech) }}">
                    <td data-label="Technicien">
                        <div style="display:flex;align-items:center;gap:10px;">
                            @if($tech->profile_photo)
                                <img src="{{ asset('storage/' . $tech->profile_photo) }}" alt="Avatar" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid var(--accent-primary);">
                            @else
                                <div class="member-avatar">{{ strtoupper(substr($tech->name, 0, 2)) }}</div>
                            @endif
                            <strong>{{ $tech->name }}</strong>
                        </div>
                    </td>
                    <td data-label="Email">{{ $tech->email }}</td>
                    <td data-label="Téléphone">{{ $tech->telephone ?? '-' }}</td>
                    <td data-label="Missions">{{ $tech->missions_count }}</td>
                    <td data-label="Disponibilité">
                        <span class="badge {{ $tech->disponible ? 'badge-disponible' : 'badge-occupe' }}">
                            <i class="las {{ $tech->disponible ? 'la-check-circle' : 'la-user-clock' }}"></i>
                            {{ $tech->disponible ? 'Disponible' : 'En mission' }}
                        </span>
                    </td>
                    <td data-label="Actions"><div class="btn-group"><a href="{{ route('admin.techniciens.show', $tech) }}" class="btn btn-secondary btn-sm"><i class="las la-eye"></i></a><form action="{{ route('admin.techniciens.destroy', $tech) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="las la-trash"></i></button></form></div></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted" style="padding:40px;">Aucun technicien.<br><a href="{{ route('admin.techniciens.create') }}" class="btn btn-primary btn-sm mt-2">Ajouter</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($techniciens->hasPages())<div class="card-footer">{{ $techniciens->withQueryString()->links() }}</div>@endif
</div>
@endsection
