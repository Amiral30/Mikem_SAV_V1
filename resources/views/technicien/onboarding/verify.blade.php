@extends('layouts.technicien')

@section('title', 'Vérification du compte')

@section('content')
<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div class="card" style="max-width: 450px; width: 100%; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <div class="card-body" style="padding: 40px; text-align: center;">
            <div style="background: var(--accent-gradient); width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; color: white; font-size: 32px;">
                <i class="las la-envelope-open-text"></i>
            </div>
            
            <h2 style="margin-bottom: 12px; font-weight: 700; color: var(--text-primary);">Vérification E-mail</h2>
            <p style="color: var(--text-muted); margin-bottom: 30px; font-size: 0.95rem;">
                Un code de vérification à 6 chiffres a été envoyé à l'adresse <br><strong>{{ auth()->user()->email }}</strong>
            </p>

            <div id="timer-container" style="margin-bottom: 25px; font-weight: 600; color: #ef4444; background: #ef444410; padding: 8px 15px; border-radius: 20px; display: inline-block;">
                <i class="las la-history"></i> Expire dans : <span id="timer">--:--</span>
            </div>

            @if(session('error'))
                <div style="background: #ef444420; color: #ef4444; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; border: 1px solid #ef444440;">
                    <i class="las la-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div style="background: #10b98120; color: #10b981; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; border: 1px solid #10b98140;">
                    <i class="las la-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('technicien.onboarding.verify') }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 12px; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; color: var(--text-muted); letter-spacing: 1px;">Saisir le code</label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                           placeholder="000000" maxlength="6" autofocus
                           style="text-align: center; font-size: 2rem; letter-spacing: 10px; font-weight: 800; padding: 15px; background: var(--bg-primary); border: 2px solid var(--border-color);">
                    @error('code')
                        <div class="invalid-feedback" style="display: block; margin-top: 8px;">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-weight: 600; font-size: 1rem;">
                    Vérifier le code <i class="las la-arrow-right" style="margin-left: 8px;"></i>
                </button>
            </form>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 10px;">
                    Vous n'avez pas reçu le code ?
                </p>
                <form action="{{ route('technicien.onboarding.resend') }}" method="POST" id="resend-form">
                    @csrf
                    <button type="submit" id="resend-btn" style="background: none; border: none; color: var(--accent-primary); font-weight: 600; cursor: pointer; padding: 0; font-size: 0.85rem; text-decoration: underline;">
                        Demander un nouveau code
                    </button>
                </form>
                <form action="{{ route('logout') }}" method="POST" style="margin-top: 15px;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: #ef4444; font-size: 0.85rem; cursor: pointer; text-decoration: underline;">
                        C'est pas moi ? Me déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timerElement = document.getElementById('timer');
        const submitBtn = document.querySelector('button[type="submit"]');
        
        // Calcul du temps restant en secondes récupéré depuis le PHP (plus robuste que les dates)
        let remainingSeconds = Math.floor({{ auth()->user()->fresh()->verification_code_expires_at ? now()->diffInSeconds(auth()->user()->fresh()->verification_code_expires_at, false) : -1 }});
        
        if (remainingSeconds <= 0) {
            timerElement.innerText = "EXPIRÉ";
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.5';
            return;
        }
        
        function updateTimer() {
            if (remainingSeconds < 0) {
                timerElement.innerText = "EXPIRÉ";
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
                submitBtn.style.cursor = 'not-allowed';
                return;
            }
            
            let minutes = Math.floor(remainingSeconds / 60);
            let seconds = Math.floor(remainingSeconds % 60);
            
            timerElement.innerText = (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds < 10 ? "0" + seconds : seconds);
            remainingSeconds--;
        }
        
        updateTimer();
        setInterval(updateTimer, 1000);

        // Gestion du bouton renvoyer
        const resendForm = document.getElementById('resend-form');
        const resendBtn = document.getElementById('resend-btn');
        
        resendForm.addEventListener('submit', function() {
            // On ne désactive pas le bouton immédiatement pour laisser le formulaire s'envoyer
            resendBtn.innerHTML = '<i class="las la-spinner la-spin"></i> Envoi en cours...';
            resendBtn.style.opacity = '0.7';
            resendBtn.style.pointerEvents = 'none'; // Empêche le double clic sans bloquer le formulaire
        });
    });
</script>
@endsection
