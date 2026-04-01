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
        if ($mission->statut !== 'en_attente') {
            return redirect()->route('admin.missions.show', $mission)->with('error', 'La mission a déjà débuté et n\'est plus modifiable.');
        }
        $techniciens = User::techniciens()->get();
        $mission->load('techniciens');
        return view('admin.missions.edit', compact('mission', 'techniciens'));
    }

    public function update(MissionRequest $request, Mission $mission)
    {
        if ($mission->statut !== 'en_attente') {
            return back()->with('error', 'La mission a déjà débuté, modification impossible.');
        }
        $data = $request->validated();
        $data['is_groupe'] = count($request->input('techniciens', [])) > 1;
        $previousTechIds = $mission->techniciens->pluck('id')->toArray();
        $mission->update($data);
        $this->syncTechniciens($mission, $request, $previousTechIds);

        return redirect()->route('admin.missions.show', $mission)
            ->with('success', 'Mission mise à jour avec succès.');
    }

    public function destroy(Mission $mission)
    {
        if ($mission->statut !== 'en_attente') {
            return back()->with('error', 'La mission a déjà débuté, suppression impossible.');
        }
        $mission->delete();

        return redirect()->route('admin.missions.index')
            ->with('success', 'Mission supprimée avec succès.');
    }

    public function updateStatut(Request $request, Mission $mission)
    {
        if ($mission->statut === 'terminee') {
            return back()->with('error', 'Impossible de modifier le statut : la mission est déjà terminée par le technicien.');
        }
        $request->validate([
            'statut' => 'required|in:en_attente,en_cours,en_pause,suspendue,terminee',
        ]);
        $mission->update(['statut' => $request->statut]);

        if (in_array($request->statut, ['terminee', 'suspendue'])) {
            $techIds = $mission->techniciens()->pluck('users.id')->toArray();
            User::whereIn('id', $techIds)->update(['disponible' => true]);
        } elseif (in_array($request->statut, ['en_cours', 'en_pause'])) {
            $techIds = $mission->techniciens()->pluck('users.id')->toArray();
            User::whereIn('id', $techIds)->update(['disponible' => false]);
        }
        return back()->with('success', 'Statut mis à jour.');
    }

    private function syncTechniciens(Mission $mission, $request, array $previousTechIds = [])
    {
        $technicienIds = $request->input('techniciens', []);
        $chefEquipeId = $request->input('chef_equipe_id');

        if (!empty($previousTechIds)) {
            $releasedIds = array_diff($previousTechIds, $technicienIds);
        }

        if (!empty($technicienIds)) {
            // Si un seul technicien, il est automatiquement chef d'équipe
            if (count($technicienIds) === 1) {
                $chefEquipeId = $technicienIds[0];
            }

            $syncData = [];
            foreach ($technicienIds as $techId) {
                $syncData[$techId] = ['is_chef_equipe' => ($techId == $chefEquipeId)];
            }
            $mission->techniciens()->sync($syncData);
            if ($chefEquipeId) {
                $mission->update(['chef_equipe_id' => $chefEquipeId]);
            }

            $newTechIds = array_diff($technicienIds, $previousTechIds);
            if (!empty($newTechIds)) {
                $newTechs = User::whereIn('id', $newTechIds)->get();
                foreach ($newTechs as $technicien) {
                    /** @var \App\Models\User $technicien */
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
