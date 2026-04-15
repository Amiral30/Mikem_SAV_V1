@extends('layouts.technicien')
@section('title', 'Mes Missions')
@section('page-title', 'Mes Missions')

@section('content')
<div class="filter-bar">
    <form action="{{ route('technicien.missions.index') }}" method="GET" style="display:flex;gap:12px;">
        <select name="statut" class="form-control" onchange="this.form.submit()">
            <option value="">Tous les statuts</option>
            @foreach(\App\Models\Mission::STATUTS as $key => $label)<option value="{{ $key }}" {{ request('statut')==$key?'selected':'' }}>{{ $label }}</option>@endforeach
        </select>
    </form>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>Mission</th><th>Type</th><th>Date</th><th>Statut</th><th>Rôle</th><th>Action</th></tr></thead>
            <tbody>
                @forelse($missions as $mission)
                <tr data-href="{{ route('technicien.missions.show', $mission) }}">
                    <td data-label="Mission"><strong>{{ $mission->titre }}</strong></td>
                    <td data-label="Type">{{ $mission->type_mission }}</td>
                    <td data-label="Date">{{ $mission->date_mission->format('d/m/Y') }}</td>
                    <td data-label="Statut">
                        <span class="badge {{ $mission->statut_class }}">
                            @switch($mission->statut)
                                @case('en_attente') <i class="las la-clock"></i> @break
                                @case('acceptee') <i class="las la-user-check"></i> @break
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
                    <td data-label="Rôle">@if($mission->pivot->is_chef_equipe)<span class="chef-badge"><i class="las la-crown text-warning"></i> Chef</span>@else<span class="text-muted">Membre</span>@endif</td>
                    <td data-label="Action"><a href="{{ route('technicien.missions.show', $mission) }}" class="btn btn-primary btn-sm">Détails</a></td>
                </tr>
                @empty<tr><td colspan="6" class="text-center text-muted" style="padding:40px;">Aucune mission.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($missions->hasPages())<div class="card-footer">{{ $missions->withQueryString()->links() }}</div>@endif
</div>
@endsection
