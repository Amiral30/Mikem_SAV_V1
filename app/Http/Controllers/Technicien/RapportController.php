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
        
        // Si le rapport existe déjà, on redirige vers l'édition plutôt que de bloquer
        $existingRapport = Rapport::where('mission_id', $mission->id)->where('user_id', $user->id)->first();
        if ($existingRapport) {
            if ($mission->statut === 'terminee') {
                return redirect()->route('technicien.rapports.show', ['mission' => $mission, 'rapport' => $existingRapport]);
            }
            return redirect()->route('technicien.rapports.edit', ['mission' => $mission, 'rapport' => $existingRapport]);
        }
        return view('technicien.rapports.create', compact('mission'));
    }

    public function store(RapportRequest $request, Mission $mission)
    {
        $user = auth()->user();
        $pivot = $mission->techniciens()->where('user_id', $user->id)->first();
        if (!$pivot || ($mission->is_groupe && !$pivot->pivot->is_chef_equipe)) {
            abort(403);
        }

        // Fiche de passage obligatoire
        $fichePath = $request->file('fiche_passage')->store('rapports/fiches', 'public');

        $fichiers = [];
        if ($request->hasFile('fichiers')) {
            $descriptions = array_values($request->input('file_descriptions', []));
            $files = array_values($request->file('fichiers'));

            foreach ($files as $index => $file) {
                $path = $file->store('rapports/' . $mission->id, 'public');
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
            'fiche_passage_path' => $fichePath,
            'fichiers' => !empty($fichiers) ? $fichiers : null,
        ]);

        // Mise à jour de la mission : statut "Soumis" et date de soumission
        $mission->update([
            'statut' => 'soumis',
            'submitted_at' => now(),
        ]);

        return redirect()->route('technicien.missions.show', $mission)
            ->with('success', 'Rapport soumis avec succès. En attente de validation par l\'Administration.');
    }

    public function edit(Mission $mission, Rapport $rapport)
    {
        if ($mission->statut === 'terminee') {
            return redirect()->route('technicien.missions.show', $mission)->with('error', 'La mission est déjà validée et ne peut plus être modifiée.');
        }
        return view('technicien.rapports.edit', compact('mission', 'rapport'));
    }

    public function update(RapportRequest $request, Mission $mission, Rapport $rapport)
    {
        if ($mission->statut === 'terminee') {
            abort(403, 'Modification interdite car la mission est validée.');
        }

        $data = $request->validated();
        
        if ($request->hasFile('fiche_passage')) {
            $data['fiche_passage_path'] = $request->file('fiche_passage')->store('rapports/fiches', 'public');
        }

        $rapport->update($data);

        // On remet la mission en statut 'soumis' (au cas où elle était 'a_modifier')
        $mission->update(['statut' => 'soumis', 'submitted_at' => now()]);

        return redirect()->route('technicien.missions.show', $mission)
            ->with('success', 'Rapport mis à jour et soumis à nouveau.');
    }

    public function show(Mission $mission, Rapport $rapport)
    {
        // Sécurité : vérifier que le technicien fait partie de cette mission
        if (!$mission->techniciens->contains(auth()->id())) {
            abort(403, 'Vous n\'êtes pas autorisé à voir ce rapport.');
        }
        $rapport->load('mission', 'user');
        return view('technicien.rapports.show', compact('mission', 'rapport'));
    }
}
