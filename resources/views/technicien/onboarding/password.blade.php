@extends('layouts.technicien')

@section('title', 'Définition du mot de passe')

@section('content')
<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div class="card" style="max-width: 450px; width: 100%; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <div class="card-body" style="padding: 40px;">
            <div style="text-align: center; margin-bottom: 30px;">
                <div style="background: var(--bg-primary); width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: var(--accent-primary); font-size: 28px; border: 1px solid var(--border-color);">
                    <i class="las la-lock"></i>
                </div>
                <h2 style="font-weight: 700; color: var(--text-primary);">Nouveau mot de passe</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 10px;">
                    Pour sécuriser votre compte, veuillez définir un mot de passe personnel que vous seul connaîtrez.
                </p>
            </div>

            <form action="{{ route('technicien.onboarding.update-password') }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.85rem; color: var(--text-muted);">Mot de passe</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           placeholder="8 caractères minimum" required
                           style="padding: 12px; font-size: 1rem; background: var(--bg-primary); border: 1px solid var(--border-color);">
                    @error('password')
                        <div class="invalid-feedback" style="display: block; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 30px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.85rem; color: var(--text-muted);">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control" 
                           placeholder="Répétez le mot de passe" required
                           style="padding: 12px; font-size: 1rem; background: var(--bg-primary); border: 1px solid var(--border-color);">
                </div>

                <ul style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 30px; padding-left: 20px;">
                    <li>Au moins 8 caractères</li>
                    <li>Au moins une lettre</li>
                    <li>Au moins un chiffre</li>
                </ul>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-weight: 600; font-size: 1rem;">
                    Finaliser mon inscription <i class="las la-check-circle" style="margin-left: 8px;"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
