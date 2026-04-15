<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\TechnicienInvitation;
use Illuminate\Validation\Rules\Password;
use Barryvdh\DomPDF\Facade\Pdf;

class TechnicienController extends Controller
{
    public function index(Request $request)
    {
        $query = User::techniciens()->withCount('missions');
        if ($request->filled('disponibilite')) {
            $query->where('disponible', $request->disponibilite === 'disponible');
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $techniciens = $query->latest()->paginate(15);
        return view('admin.techniciens.index', compact('techniciens'));
    }

    public function create()
    {
        return view('admin.techniciens.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'nullable|string|max:20',
        ]);

        $defaultPassword = 'tech123';
        // Génération du code à 6 chiffres
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'password' => Hash::make($defaultPassword),
            'role' => 'technicien',
            'disponible' => true,
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => now()->addMinutes(5),
            'onboarding_completed' => false,
        ]);

        // Envoi de l'email d'invitation
        Mail::to($user->email)->send(new TechnicienInvitation($user, $defaultPassword));

        return redirect()->route('admin.techniciens.index')
            ->with('success', 'Technicien créé et invitation envoyée par email.');
    }

    public function show(User $technicien)
    {
        $technicien->load(['missions' => function ($q) {
            $q->latest()->take(20);
        }, 'rapports.mission']);
        return view('admin.techniciens.show', compact('technicien'));
    }

    /* 
       Note: Les fonctions edit() et update() ont été retirées car seul 
       le technicien est autorisé à modifier ses informations personnelles.
    */

    public function destroy(User $technicien)
    {
        $technicien->delete();
        return redirect()->route('admin.techniciens.index')
            ->with('success', 'Technicien supprimé.');
    }

    public function exportPdf(User $technicien)
    {
        $technicien->load('missions');
        $totalDeplacement = $technicien->missions->sum('prix_deplacement');

        $pdf = Pdf::loadView('admin.techniciens.pdf', compact('technicien', 'totalDeplacement'));
        return $pdf->download('rapport_technicien_' . $technicien->id . '.pdf');
    }
}
