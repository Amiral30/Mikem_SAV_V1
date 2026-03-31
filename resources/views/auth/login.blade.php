<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - SAV Mikem</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="login-page">
        <div class="login-card">
            <div class="login-logo">
                <h1>🔧 SAV Mikem</h1>
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
