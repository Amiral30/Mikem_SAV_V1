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
                <tr>
                    <td><div style="display:flex;align-items:center;gap:10px;"><div class="member-avatar">{{ strtoupper(substr($tech->name, 0, 2)) }}</div><a href="{{ route('admin.techniciens.show', $tech) }}" style="color:var(--text-primary);font-weight:600;">{{ $tech->name }}</a></div></td>
                    <td>{{ $tech->email }}</td>
                    <td>{{ $tech->telephone ?? '-' }}</td>
                    <td>{{ $tech->missions_count }}</td>
                    <td><span class="badge {{ $tech->disponible?'badge-disponible':'badge-occupe' }}">{{ $tech->disponible?'Disponible':'En mission' }}</span></td>
                    <td><div class="btn-group"><a href="{{ route('admin.techniciens.show', $tech) }}" class="btn btn-secondary btn-sm"><i class="las la-eye"></i></a><a href="{{ route('admin.techniciens.edit', $tech) }}" class="btn btn-secondary btn-sm"><i class="las la-pen"></i></a><form action="{{ route('admin.techniciens.destroy', $tech) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="las la-trash"></i></button></form></div></td>
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
