<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_missions' => Mission::count(),
            'missions_en_cours' => Mission::where('statut', 'en_cours')->count(),
            'missions_terminees' => Mission::where('statut', 'terminee')->count(),
            'missions_en_attente' => Mission::where('statut', 'en_attente')->count(),
            'missions_en_pause' => Mission::where('statut', 'en_pause')->count(),
            'techniciens_disponibles' => User::techniciens()->disponibles()->count(),
            'total_techniciens' => User::techniciens()->count(),
        ];

        $recentMissions = Mission::with('techniciens', 'chefEquipe')
            ->latest()->take(10)->get();

        $techniciens = User::techniciens()->withCount('missions')->get();

        // Data for Line Chart (7 last days)
        $chartDates = [];
        $chartMissions = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartDates[] = now()->subDays($i)->format('d/m');
            $chartMissions[] = Mission::whereDate('created_at', $date)->count();
        }

        return view('admin.dashboard', compact('stats', 'recentMissions', 'techniciens', 'chartDates', 'chartMissions'));
    }
}
