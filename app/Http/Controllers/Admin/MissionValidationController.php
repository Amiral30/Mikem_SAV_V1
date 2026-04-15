<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use Illuminate\Http\Request;

class MissionValidationController extends Controller
{
    /**
     * Valider définitivement le rapport — la mission est "Terminée"
     */
    public function valider(Request $request, Mission $mission)
    {
        if ($mission->statut !== 'soumis') {
            return back()->with('error', 'Ce rapport ne peut pas être validé dans son état actuel.');
        }

        $mission->update([
            'statut' => 'terminee',
            'validated_at' => now(),
        ]);

        // Remettre les techniciens disponibles
        $techIds = $mission->techniciens->pluck('id')->toArray();
        \App\Models\User::whereIn('id', $techIds)->update(['disponible' => true]);

        // Enregistrer un éventuel commentaire de validation
        if ($request->filled('admin_notes')) {
            $rapport = $mission->rapports()->latest()->first();
            if ($rapport) {
                $rapport->update(['admin_notes' => $request->admin_notes]);
            }
        }

        return back()->with('success', 'Mission validée et clôturée avec succès. ✅');
    }

    /**
     * Demander une correction — la mission revient en statut "À corriger"
     */
    public function rejeter(Request $request, Mission $mission)
    {
        $request->validate([
            'admin_notes' => 'required|string|min:10',
        ], [
            'admin_notes.required' => 'Vous devez indiquer la raison de la demande de correction.',
            'admin_notes.min' => 'Votre remarque doit faire au moins 10 caractères.',
        ]);

        if (!in_array($mission->statut, ['soumis', 'a_modifier'])) {
            return back()->with('error', 'Ce rapport ne peut pas être rejeté dans son état actuel.');
        }

        // Enregistrer les notes de l'Admin dans le rapport
        $rapport = $mission->rapports()->latest()->first();
        if ($rapport) {
            $rapport->update(['admin_notes' => $request->admin_notes]);
        }

        $mission->update(['statut' => 'a_modifier']);

        return back()->with('success', 'Demande de correction envoyée au technicien. Il recevra une notification.');
    }
}
