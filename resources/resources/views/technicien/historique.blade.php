@extends('layouts.technicien')

@section('title', 'Historique')
@section('page-title', 'Historique des Missions')
@section('page-subtitle', 'Vos missions terminées')

@section('content')
<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Mission</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Adresse</th>
                    <th>Rapport</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($missions as $mission)
                @php $rapport = $mission->rapports->where('user_id', auth()->id())->first(); @endphp
                <tr>
                    <td style="font-weight:600;">{{ $mission->titre }}</td>
                    <td>{{ $mission->type_mission }}</td>
                    <td>{{ $mission->date_mission->format('d/m/Y') }}</td>
                    <td>{{ Str::limit($mission->adresse, 25) }}</td>
                    <td>
                        @if($rapport)
                            <span class="badge badge-success">Soumis</span>
                        @else
                            <a href="{{ route('technicien.rapports.create', $mission) }}" class="badge badge-warning" style="text-decoration:none;">À rédiger</a>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('technicien.missions.show', $mission) }}" class="btn btn-secondary btn-sm">👁️</a>
                            @if($rapport)
                            <a href="{{ route('technicien.rapports.show', ['mission' => $mission, 'rapport' => $rapport]) }}" class="btn btn-secondary btn-sm">📝</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted" style="padding:40px;">Aucune mission terminée dans votre historique.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($missions->hasPages())
    <div class="card-footer">{{ $missions->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
