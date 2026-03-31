<?php

namespace App\Http\Controllers\Technicien;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = $user->missions()->with('chefEquipe', 'techniciens');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $missions = $query->latest()->paginate(15);
        return view('technicien.missions.index', compact('missions'));
    }

    public function show(Mission $mission)
    {
        $user = auth()->user();
        if (!$mission->techniciens->contains($user->id)) {
            abort(403, 'Vous n\'êtes pas assigné à cette mission.');
        }

        $mission->load('techniciens', 'chefEquipe', 'rapports.user');
        return view('technicien.missions.show', compact('mission'));
    }

    public function accept(Mission $mission)
    {
        $user = auth()->user();
        $pivot = $mission->techniciens()->where('user_id', $user->id)->first();

        if (!$pivot || !$pivot->pivot->is_chef_equipe) {
            abort(403, 'Seul le chef d\'équipe peut accepter la mission.');
        }

        $mission->techniciens()->updateExistingPivot($user->id, ['accepted' => true]);

        if ($mission->statut === 'en_attente') {
            $mission->update(['statut' => 'en_cours']);
        }

        return back()->with('success', 'Mission acceptée.');
    }

    public function updateStatut(Request $request, Mission $mission)
    {
        $user = auth()->user();
        if (!$mission->techniciens->contains($user->id)) {
            abort(403, 'Vous n\'êtes pas assigné à cette mission.');
        }

        $request->validate([
            'statut' => 'required|in:en_cours,en_pause,terminee',
        ]);

        $mission->update(['statut' => $request->statut]);

        if ($request->statut === 'terminee') {
            $techIds = $mission->techniciens->pluck('id')->toArray();
            User::whereIn('id', $techIds)->update(['disponible' => true]);
        }

        return back()->with('success', 'Statut mis à jour.');
    }

    public function historique()
    {
        $user = auth()->user();
        $missions = $user->missions()
            ->where('statut', 'terminee')
            ->with('rapports', 'chefEquipe')
            ->latest()
            ->paginate(15);

        return view('technicien.historique', compact('missions'));
    }
}
