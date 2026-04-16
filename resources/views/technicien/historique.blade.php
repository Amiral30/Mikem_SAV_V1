@extends('layouts.technicien')
@section('title', 'Historique')
@section('page-title', 'Historique des Missions')
@section('page-subtitle', 'Consultez vos interventions passées et vos rapports')

@section('styles')
<style>
    /* Force l'icône du calendrier natif à devenir blanche en mode sombre */
    [data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }
</style>
@endsection

@section('content')
<div class="filter-bar" style="margin-bottom: 25px; display: flex; justify-content: center;">
    <form action="{{ route('technicien.historique') }}" method="GET" style="display:flex;gap:8px; width:100%; max-width: 400px; align-items: center;">
        <div class="search-input" style="flex:1; position: relative;">
            <input type="date" name="date" class="form-control" value="{{ request('date') }}" onchange="this.form.submit()" placeholder="Chercher une date" style="padding-left: 20px; border-radius: 50px; background: var(--bg-secondary); color: var(--text-primary); border: 1px solid var(--border-color); height: 45px;">
        </div>
        @if(request('date'))
            <a href="{{ route('technicien.historique') }}" class="btn btn-secondary" style="border-radius: 50px; height: 45px; display: flex; align-items: center; justify-content: center; width: 45px; padding: 0;" title="Effacer le filtre">
                <i class="las la-times"></i>
            </a>
        @endif
    </form>
</div>

<div class="card" style="margin: 0 auto; max-width: 800px; background: var(--bg-secondary); border: 1px solid var(--border-color);">
    <div class="card-header" style="padding: 15px 20px; background: rgba(0,0,0,0.03); border-bottom: 1px solid var(--border-color);">
        <h4 style="margin:0; font-size: 0.9rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px;">Archives des missions</h4>
    </div>
    @if($missions->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Mission / Client</th>
                        <th>Date</th>
                        <th>Rapport</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($missions as $mission)
                    @php $rapport = $mission->rapports->where('user_id', auth()->id())->first(); @endphp
                    <tr>
                        <td data-label="Mission">
                            <div style="font-weight:700; color: var(--text-primary);">{{ $mission->titre }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);"><i class="las la-user-tie"></i> {{ $mission->client_nom }}</div>
                        </td>
                        <td data-label="Date">
                            <div style="font-weight:500;">{{ $mission->date_mission->format('d/m/Y') }}</div>
                        </td>
                        <td data-label="Rapport">
                            @if($rapport)
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid #10b98130;">
                                    <i class="las la-check-circle"></i> Soumis
                                </span>
                            @else
                                <a href="{{ route('technicien.rapports.create', $mission) }}" class="badge" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid #f59e0b30; text-decoration:none;">
                                    <i class="las la-edit"></i> À rédiger
                                </a>
                            @endif
                        </td>
                        <td data-label="Action" style="text-align: right;">
                            <div class="btn-group">
                                <a href="{{ route('technicien.missions.show', $mission) }}" class="btn btn-secondary btn-sm" title="Détail mission">
                                    <i class="las la-eye"></i>
                                </a>
                                @if($rapport)
                                <a href="{{ route('technicien.rapports.show', ['mission'=>$mission,'rapport'=>$rapport]) }}" class="btn btn-primary btn-sm" title="Voir rapport">
                                    <i class="las la-file-alt"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="card-body" style="padding: 60px 20px; text-align: center;">
            <i class="las la-history" style="font-size: 4rem; color: var(--text-muted); opacity: 0.2;"></i>
            <p style="margin-top: 20px; color: var(--text-muted); font-size: 1rem;">Aucun historique correspondant.</p>
        </div>
    @endif
    @if($missions->hasPages())
        <div class="card-footer" style="padding: 15px 20px;">
            {{ $missions->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
