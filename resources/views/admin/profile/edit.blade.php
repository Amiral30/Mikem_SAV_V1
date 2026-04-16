@extends('layouts.admin')
@section('title', 'Paramètres')
@section('page-title', 'Paramètres')
@section('page-subtitle', 'Sécurité du compte administrateur')

@section('content')
<div style="max-width: 500px; margin: 0 auto;">
    <div class="card">
        <div class="card-header" style="padding: 20px 24px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 12px;">
            <div style="width: 42px; height: 42px; background: var(--accent-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="las la-lock" style="font-size: 1.3rem; color: white;"></i>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1rem; color: var(--text-primary);">Changer le mot de passe</h3>
                <p style="margin: 0; font-size: 0.8rem; color: var(--text-muted);">Connecté en tant que : <strong>{{ auth()->user()->name }}</strong></p>
            </div>
        </div>

        <div class="card-body" style="padding: 28px 24px;">
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="current_password">
                        <i class="las la-key"></i> Mot de passe actuel
                    </label>
                    <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Votre mot de passe actuel" autocomplete="current-password" required>
                    @error('current_password')
                        <div class="invalid-feedback" style="color: var(--danger); font-size: 0.82rem; margin-top: 5px;">
                            <i class="las la-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="las la-lock"></i> Nouveau mot de passe
                    </label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Au moins 8 caractères" autocomplete="new-password" required>
                    @error('password')
                        <div class="invalid-feedback" style="color: var(--danger); font-size: 0.82rem; margin-top: 5px;">
                            <i class="las la-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">
                        <i class="las la-check-circle"></i> Confirmer le nouveau mot de passe
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Répétez le nouveau mot de passe" autocomplete="new-password" required>
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="las la-save"></i> Mettre à jour le mot de passe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
