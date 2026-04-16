@extends('layouts.technicien')
@section('title', 'Mes Missions')
@section('page-title', 'Mes Missions')

@section('content')
<div class="filter-bar">
    <form action="{{ route('technicien.missions.index') }}" method="GET" style="display:flex;gap:12px;">
        <select name="statut" class="form-control" onchange="this.form.submit()">
            <option value="">Tous les statuts</option>
            @foreach(['en_attente'=>'En attente', 'en_cours'=>'En cours', 'en_pause'=>'En pause', 'acceptee'=>'Acceptée', 'suspendue'=>'Suspendue'] as $key => $label)
                <option value="{{ $key }}" {{ request('statut')==$key?'selected':'' }}>{{ $label }}</option>
            @endforeach
        </select>
    </form>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>Mission</th><th>Date</th><th>Statut</th><th>Rôle</th><th>Action</th></tr></thead>
            <tbody>
                @forelse($missions as $mission)
                <tr data-href="{{ route('technicien.missions.show', $mission) }}">
                    <td data-label="Mission">
                        <strong>{{ $mission->titre }}</strong>
                        <div style="font-size:0.8rem; color: var(--text-muted);">{{ $mission->client_nom }}</div>
                    </td>
                    <td data-label="Date">{{ $mission->date_mission->format('d/m/Y') }}</td>
                    <td data-label="Statut">
                        <span class="badge {{ $mission->statut_class }}">{{ $mission->statut_label }}</span>
                    </td>
                    <td data-label="Rôle">
                        @if($mission->pivot->is_chef_equipe)
                            <span style="color: #f59e0b;"><i class="las la-crown"></i> Chef</span>
                        @else
                            <span class="text-muted">Membre</span>
                        @endif
                    </td>
                    <td data-label="Action">
                        <a href="{{ route('technicien.missions.show', $mission) }}" class="btn btn-primary btn-sm">Détails</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted" style="padding:40px;">Aucune mission active.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($missions->hasPages())
        <div class="card-footer">{{ $missions->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
