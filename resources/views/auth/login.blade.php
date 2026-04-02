<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - SAV Mikem</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .cb-slideshow,
        .cb-slideshow:after {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0px;
            left: 0px;
            z-index: 0;
            list-style-type: none;
            margin: 0;
            padding: 0;
        }
        .cb-slideshow:after {
            content: '';
            background: rgba(0,0,0,0.5); /* Masque sombre pour lisibilité */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        .cb-slideshow li span {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0px;
            left: 0px;
            color: transparent;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
            z-index: 0;
            animation: imageAnimation 18s linear infinite 0s;
        }
        .cb-slideshow li:nth-child(1) span {
            background-image: url('{{ asset("images/bg-network.png") }}');
        }
        .cb-slideshow li:nth-child(2) span {
            background-image: url('{{ asset("images/bg-computer.png") }}');
            animation-delay: 6s;
        }
        .cb-slideshow li:nth-child(3) span {
            background-image: url('{{ asset("images/bg-electro.png") }}');
            animation-delay: 12s;
        }
        @keyframes imageAnimation {
            0% { opacity: 0; animation-timing-function: ease-in; transform: scale(1); }
            8% { opacity: 1; animation-timing-function: ease-out; }
            33% { opacity: 1; }
            41% { opacity: 0; transform: scale(1.05); }
            100% { opacity: 0; transform: scale(1); }
        }
        .login-page {
            background: transparent !important;
            z-index: 2;
            position: relative;
        }
        .login-page::before, .login-page::after {
            display: none !important;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.85); /* Légèrement transparent */
            backdrop-filter: blur(15px);
        }
    </style>
</head>
<body>
    <ul class="cb-slideshow">
        <li><span></span></li>
        <li><span></span></li>
        <li><span></span></li>
    </ul>
    <div class="login-page">
        <div class="login-card">
            <div class="login-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Mikem Technologie" style="height: auto; width: 100%; max-width: 220px; margin-bottom: 20px;">
                <p>Gestion des missions d'intervention</p>
            </div>
            @if($errors->any())
                <div class="alert alert-danger">❌ {{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="votre@email.com" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember" style="margin-bottom:0; cursor:pointer;">Se souvenir de moi</label>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Se connecter</button>
            </form>
        </div>
    </div>
</body>
</html>
