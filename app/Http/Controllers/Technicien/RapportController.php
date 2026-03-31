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
        if (!$mission->techniciens->contains($user->id)) {
            abort(403);
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
        if (!$mission->techniciens->contains($user->id)) {
            abort(403);
        }
        $fichiers = [];
        if ($request->hasFile('fichiers')) {
            foreach ($request->file('fichiers') as $file) {
                $path = $file->store('rapports/' . $mission->id, 'public');
                $fichiers[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
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
