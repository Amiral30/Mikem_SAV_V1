<?php

namespace App\Http\Controllers\Technicien;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use Illuminate\Validation\Rules\Password;

class OnboardingController extends Controller
{
    public function showVerify()
    {
        if (auth()->user()->onboarding_completed) {
            return redirect()->route('technicien.dashboard');
        }
        return view('technicien.onboarding.verify');
    }

    public function processVerify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        if ($request->code !== auth()->user()->verification_code) {
            return back()->withErrors(['code' => 'Le code de vérification est incorrect.']);
        }

        if (now()->gt(auth()->user()->verification_code_expires_at)) {
            return back()->withErrors(['code' => 'Ce code a expiré. Veuillez en demander un nouveau.']);
        }

        session(['onboarding_verified' => true]);
        return redirect()->route('technicien.onboarding.password');
    }

    public function showPassword()
    {
        if (!session('onboarding_verified')) {
            return redirect()->route('technicien.onboarding.show');
        }
        return view('technicien.onboarding.password');
    }

    public function processPassword(Request $request)
    {
        if (!session('onboarding_verified')) {
            return redirect()->route('technicien.onboarding.show');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->password),
            'onboarding_completed' => true,
            'verification_code' => null, // Nettoyage
        ]);

        session()->forget('onboarding_verified');

        return redirect()->route('technicien.dashboard')
            ->with('success', 'Bienvenue ! Votre compte est maintenant activé.');
    }

    public function resendCode()
    {
        $user = auth()->user();
        
        // Génération d'un nouveau code
        $newCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update([
            'verification_code' => $newCode,
            'verification_code_expires_at' => now()->addMinutes(15), // Passage à 15 minutes pour compenser les décalages serveurs
        ]);

        try {
            Mail::to($user->email)->send(new VerificationCodeMail($user, $newCode));
            return redirect()->route('technicien.onboarding.show')
                ->with('success', 'Un nouveau code a été envoyé à ' . $user->email);
        } catch (\Exception $e) {
            // Si l'envoi échoue, on prévient l'utilisateur au lieu de faire une erreur 500
            return redirect()->route('technicien.onboarding.show')
                ->with('error', 'Impossible d\'envoyer l\'email. Veuillez vérifier votre connexion ou contacter un administrateur.');
        }
    }
}
