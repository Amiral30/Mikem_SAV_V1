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

        $hasActive = $user->missions()->whereIn('statut', ['en_cours', 'en_pause'])->exists();
        if ($hasActive) {
            return back()->with('error', 'Vous avez déjà une mission en cours. Terminez-la avant de démarrer une nouvelle mission.');
        }

        $mission->techniciens()->updateExistingPivot($user->id, ['accepted' => true]);
        if ($mission->statut === 'en_attente') {
            $mission->update([
                'statut' => 'en_cours',
                'started_at' => now(), // Capte l'heure de début
            ]);
            $techIds = $mission->techniciens->pluck('id')->toArray();
            User::whereIn('id', $techIds)->update(['disponible' => false]);
        }
        return back()->with('success', 'Mission acceptée avec succès.');
    }

    public function updateStatut(Request $request, Mission $mission)
    {
        $user = auth()->user();
        $pivot = $mission->techniciens()->where('user_id', $user->id)->first();
        if (!$pivot) {
            return back()->with('error', 'Vous n\'êtes pas assigné à cette mission.');
        }
        if ($mission->is_groupe && !$pivot->pivot->is_chef_equipe) {
            return back()->with('error', 'Vous ne pouvez pas modifier le statut car vous n\'êtes pas chef d\'équipe !');
        }
        if ($mission->statut === 'en_attente') {
            return back()->with('error', 'Vous devez d\'abord accepter la mission avant de pouvoir modifier son statut.');
        }
        $request->validate(['statut' => 'required|in:en_cours,en_pause,terminee']);

        if (in_array($request->statut, ['en_cours', 'en_pause'])) {
            $hasActive = $user->missions()->whereIn('statut', ['en_cours', 'en_pause'])->where('missions.id', '!=', $mission->id)->exists();
            if ($hasActive) {
                return back()->with('error', 'Vous avez déjà une autre mission en cours. Terminez-la avant de démarrer celle-ci.');
            }
        }

        // Logique spéciale pour "Terminée" (V13 Workflow)
        if ($request->statut === 'terminee') {
            // Selon ta demande : on garde 'en_cours' mais on capte la date de fin technique
            $mission->update([
                'work_finished_at' => now(),
            ]);
            return back()->with('success', 'Travail physique terminé. Veuillez rédiger votre rapport maintenant.');
        }

        $mission->update(['statut' => $request->statut]);

        if (in_array($request->statut, ['en_cours', 'en_pause'])) {
            $techIds = $mission->techniciens->pluck('id')->toArray();
            User::whereIn('id', $techIds)->update(['disponible' => false]);
        }
        return back()->with('success', 'Statut mis à jour.');
    }

    public function historique()
    {
        $user = auth()->user();
        $missions = $user->missions()
            ->where('statut', 'terminee')
            ->with('rapports', 'chefEquipe')
            ->latest()->paginate(15);
        return view('technicien.historique', compact('missions'));
    }
}
