<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            \Illuminate\Support\Facades\Log::info('Tentative de connexion réussie', [
                'email' => $user->email,
                'role' => $user->role,
                'is_admin' => $user->isAdmin(),
                'is_technicien' => $user->isTechnicien()
            ]);
            
            return $this->redirectByRole();
        }

        \Illuminate\Support\Facades\Log::warning('Tentative de connexion échouée', ['email' => $credentials['email']]);

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    private function redirectByRole()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Si c'est un technicien qui n'a pas fini son onboarding
        if ($user->isTechnicien() && !$user->onboarding_completed) {
            return redirect()->route('technicien.onboarding.show');
        }

        return redirect()->route('technicien.dashboard');
    }
}
