<?php

namespace App\Http\Controllers\Technicien;

use App\Http\Controllers\Controller;
use App\Http\Requests\RapportRequest;
use App\Models\Mission;
use App\Models\Rapport;

class RapportController extends Controller
{
    public function create(Mission $mission)
    {
        $user = auth()->user();
        $pivot = $mission->techniciens()->where('user_id', $user->id)->first();
        if (!$pivot) {
            return back()->with('error', 'Accès non autorisé.');
        }
        if ($mission->is_groupe && !$pivot->pivot->is_chef_equipe) {
            return back()->with('error', 'Seul le chef d\'équipe est habilité à rédiger le rapport final.');
        }
        $existingRapport = Rapport::where('mission_id', $mission->id)->where('user_id', $user->id)->first();
        if ($existingRapport) {
            return redirect()->route('technicien.rapports.show', ['mission' => $mission, 'rapport' => $existingRapport])
                ->with('info', 'Vous avez déjà soumis un rapport.');
        }
        return view('technicien.rapports.create', compact('mission'));
    }

    public function store(RapportRequest $request, Mission $mission)
    {
        $user = auth()->user();
        $pivot = $mission->techniciens()->where('user_id', $user->id)->first();
        if (!$pivot) {
            abort(403);
        }
        if ($mission->is_groupe && !$pivot->pivot->is_chef_equipe) {
            abort(403, 'Seul le chef d\'équipe peut soumettre le rapport.');
        }

        // Empêcher les doublons
        $existingRapport = Rapport::where('mission_id', $mission->id)->where('user_id', $user->id)->first();
        if ($existingRapport) {
            return redirect()->route('technicien.missions.show', $mission)
                ->with('error', 'Un rapport a déjà été soumis pour cette mission.');
        }

        $fichiers = [];
        if ($request->hasFile('fichiers')) {
            $descriptions = array_values($request->input('file_descriptions', []));
            $files = array_values($request->file('fichiers'));
            
            foreach ($files as $index => $file) {
                $path = $file->store('rapports/' . $mission->id, 'public');
                // Utilisation de l'index pour faire correspondre la description
                $customName = (isset($descriptions[$index]) && !empty($descriptions[$index])) 
                               ? $descriptions[$index] 
                               : $file->getClientOriginalName();
                
                $fichiers[] = [
                    'path' => $path,
                    'name' => $customName,
                    'original_name' => $file->getClientOriginalName(),
                    'type' => $file->getClientMimeType(),
                ];
            }
        }
        Rapport::create([
            'mission_id' => $mission->id,
            'user_id' => $user->id,
            'deroulement' => $request->deroulement,
            'difficultes' => $request->difficultes,
            'actions_realisees' => $request->actions_realisees,
            'fichiers' => !empty($fichiers) ? $fichiers : null,
        ]);
        return redirect()->route('technicien.missions.show', $mission)
            ->with('success', 'Rapport soumis avec succès.');
    }

    public function show(Mission $mission, Rapport $rapport)
    {
        $rapport->load('mission', 'user');
        return view('technicien.rapports.show', compact('mission', 'rapport'));
    }
}
