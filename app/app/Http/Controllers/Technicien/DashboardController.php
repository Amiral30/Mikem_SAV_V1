<?php

namespace App\Http\Controllers\Technicien;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $missions = $user->missions()
            ->whereIn('statut', ['en_attente', 'en_cours', 'en_pause'])
            ->with('chefEquipe', 'techniciens')
            ->latest()
            ->get();

        $stats = [
            'total_assignees' => $user->missions()->count(),
            'en_cours' => $user->missions()->where('statut', 'en_cours')->count(),
            'terminees' => $user->missions()->where('statut', 'terminee')->count(),
            'en_attente' => $user->missions()->where('statut', 'en_attente')->count(),
        ];

        return view('technicien.dashboard', compact('missions', 'stats'));
    }
}
