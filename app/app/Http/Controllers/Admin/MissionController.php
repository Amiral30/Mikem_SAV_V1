<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MissionRequest;
use App\Mail\MissionAssignee;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Mission::with('techniciens', 'chefEquipe');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('adresse', 'like', "%{$search}%");
            });
        }

        $missions = $query->latest()->paginate(15);
        return view('admin.missions.index', compact('missions'));
    }

    public function create()
    {
        $techniciens = User::techniciens()->get();
        return view('admin.missions.create', compact('techniciens'));
    }

    public function store(MissionRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $data['is_groupe'] = count($request->input('techniciens', [])) > 1;

        $mission = Mission::create($data);

        $this->syncTechniciens($mission, $request);

        return redirect()->route('admin.missions.index')
            ->with('success', 'Mission créée avec succès.');
    }

    public function show(Mission $mission)
    {
        $mission->load('techniciens', 'chefEquipe', 'rapports.user', 'createur');
        return view('admin.missions.show', compact('mission'));
    }

    public function edit(Mission $mission)
    {
        $techniciens = User::techniciens()->get();
        $mission->load('techniciens');
        return view('admin.missions.edit', compact('mission', 'techniciens'));
    }

    public function update(MissionRequest $request, Mission $mission)
    {
        $data = $request->validated();
        $data['is_groupe'] = count($request->input('techniciens', [])) > 1;

        // Release previously assigned technicians
        $previousTechIds = $mission->techniciens->pluck('id')->toArray();

        $mission->update($data);
        $this->syncTechniciens($mission, $request, $previousTechIds);

        return redirect()->route('admin.missions.show', $mission)
            ->with('success', 'Mission mise à jour avec succès.');
    }

    public function destroy(Mission $mission)
    {
        $techIds = $mission->techniciens->pluck('id')->toArray();
        User::whereIn('id', $techIds)->update(['disponible' => true]);
        $mission->delete();

        return redirect()->route('admin.missions.index')
            ->with('success', 'Mission supprimée avec succès.');
    }

    public function updateStatut(Request $request, Mission $mission)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,en_cours,en_pause,suspendue,terminee',
        ]);

        $mission->update(['statut' => $request->statut]);

        if ($request->statut === 'terminee') {
            $techIds = $mission->techniciens->pluck('id')->toArray();
            User::whereIn('id', $techIds)->update(['disponible' => true]);
        }

        return back()->with('success', 'Statut mis à jour.');
    }

    private function syncTechniciens(Mission $mission, $request, array $previousTechIds = [])
    {
        $technicienIds = $request->input('techniciens', []);
        $chefEquipeId = $request->input('chef_equipe_id');

        if (!empty($previousTechIds)) {
            $releasedIds = array_diff($previousTechIds, $technicienIds);
            User::whereIn('id', $releasedIds)->update(['disponible' => true]);
        }

        if (!empty($technicienIds)) {
            $syncData = [];
            foreach ($technicienIds as $techId) {
                $syncData[$techId] = [
                    'is_chef_equipe' => ($techId == $chefEquipeId),
                ];
            }
            $mission->techniciens()->sync($syncData);

            if ($chefEquipeId) {
                $mission->update(['chef_equipe_id' => $chefEquipeId]);
            }

            User::whereIn('id', $technicienIds)->update(['disponible' => false]);

            // Email notifications for newly assigned technicians
            $newTechIds = array_diff($technicienIds, $previousTechIds);
            if (!empty($newTechIds)) {
                $newTechs = User::whereIn('id', $newTechIds)->get();
                foreach ($newTechs as $technicien) {
                    try {
                        Mail::to($technicien->email)->send(new MissionAssignee($mission, $technicien));
                    } catch (\Exception $e) {
                        Log::error('Erreur envoi mail: ' . $e->getMessage());
                    }
                }
            }
        } else {
            $mission->techniciens()->detach();
        }
    }
}
