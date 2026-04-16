<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Mikem Technologie</title>
    <link rel="icon" type="image/png" href="/images/minilogo.png">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0f3460; /* Bleu marine */
            --accent: #195eac;  /* Bleu accent (pour remplacer le rouge) */
            --accent-hover: #144983;
            --text-main: #1a1a2e;
            --text-light: #64748b;
            --bg-body: #ffffff;
            --bg-input: #f8fafc;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* Split Screen Layout */
        .split-layout {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        /* Left Side : Image */
        .split-left {
            flex: 1.2;
            position: relative;
            /* Photo Unsplash (Serveurs / IT / Tech) */
            background-image: url('https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
        }

        .split-left::before {
            content: '';
            position: absolute;
            inset: 0;
            /* Gradient overlay Bleu marine vers Rouge subtil pour l'élégance */
            background: linear-gradient(135deg, rgba(15, 52, 96, 0.85) 0%, rgba(26, 26, 46, 0.7) 100%);
        }

        .side-content {
            position: relative;
            z-index: 10;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
            color: white;
        }

        .side-content h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: -1px;
            line-height: 1.2;
        }

        .side-content p {
            font-size: 1.2rem;
            font-weight: 300;
            opacity: 0.9;
            max-width: 450px;
            line-height: 1.6;
        }

        /* Right Side : Login Form */
        .split-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: var(--bg-body);
            /* Motif Hexagonal industriel (Filigrane très léger 3%) */
            background-image: url("data:image/svg+xml,%3Csvg width='52' height='30' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23195eac' fill-opacity='0.04'%3E%3Cpath d='M26 0l26 15-26 15L0 15 26 0zm0 2l-22.5 13L26 28l22.5-13L26 2z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            padding: 2rem;
            position: relative;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 2rem;
        }

        .logo-wrap {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .logo-wrap img {
            height: auto;
            max-height: 85px; /* Agrandit significativement le logo ! */
            max-width: 100%;
            object-fit: contain;
            margin-bottom: 1.5rem;
        }

        .login-container h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .subtitle {
            color: var(--text-light);
            font-size: 0.95rem;
            margin-bottom: 2.5rem;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border-left: 4px solid var(--accent);
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1rem;
            font-size: 1rem;
            color: var(--text-main);
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-control:focus {
            background-color: transparent;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(15, 52, 96, 0.1);
        }

        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            gap: 8px;
        }

        .form-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .form-check label {
            font-size: 0.9rem;
            color: var(--text-light);
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            background-color: var(--accent);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(25, 94, 172, 0.2);
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(25, 94, 172, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Responsive Design */
        @media (max-width: 900px) {
            .split-layout {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
            }
            .split-left {
                flex: none;
                height: 45vh; /* Plus d'espace pour tes titres personnalisés */
                min-height: 380px;
                display: block;
            }
            .side-content {
                padding: 2.5rem 2rem;
                justify-content: flex-start;
                align-items: center;
                text-align: center;
            }
            .side-content h1 {
                font-size: 2rem;
            }
            .side-content h2 {
                font-size: 1.2rem;
                margin-top: 5px;
            }
            .split-right {
                flex: 1;
                background-color: #f8fafc;
                padding: 0 1rem 2rem 1rem;
                align-items: center;
            }
            .login-container {
                background: white;
                padding: 2rem;
                border-radius: 16px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.05);
                margin-top: 25px; /* On pousse le formulaire vers le bas pour ne plus chevaucher le texte */
                z-index: 20;
                position: relative;
            }
            .login-footer {
                position: static !important;
                margin-top: 30px;
                padding-bottom: 20px;
            }
        }

        .login-footer {
            position: absolute;
            bottom: 20px;
            text-align: center;
            color: var(--text-light);
            font-size: 0.8rem;
            opacity: 0.7;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="split-layout">
        <div class="split-left">
            <div class="side-content">
                <h1>Mikem SAV Intervention Manager</h1>
                <h2>Optimisez la gestion de vos interventions en toute sécurité.</h2><br><br><br>  
                <!-- <p>Une plateforme fiable, conçue pour les professionnels.</p> -->
                <p>L’excellence dans la gestion des interventions techniques.</p>
                <p>Sécurité, fiabilité et efficacité au cœur du système.</p>
            </div>
        </div>
        
        <div class="split-right">
            <div class="login-container">
                <div class="logo-wrap">
                    <!-- Utilisation du grand logo Mikem -->
                    <img src="/images/logom.png" alt="Mikem Technologie">
                </div>
                
                <h2>Bienvenue</h2>
                <p class="subtitle">Connectez-vous pour accéder à votre espace.</p>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <span>❌</span>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="/login">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="techniciensav@gmail.com" value="{{ old('email') }}" required autofocus autocomplete="email">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <div style="position: relative;">
                            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password" style="padding-right: 40px;">
                            <button type="button" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-light); padding: 0; display: flex;" title="Afficher/Masquer le mot de passe">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Se souvenir de moi</label>
                    </div>
                    
                    <button type="submit" class="btn-submit">Se connecter</button>
                </form>

                <script>
                    const togglePassword = document.querySelector('#togglePassword');
                    const password = document.querySelector('#password');
                    const eyeIcon = document.querySelector('#eyeIcon');

                    togglePassword.addEventListener('click', function () {
                        // Bascule le type de l'input
                        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                        password.setAttribute('type', type);
                        
                        // Bascule l'icône de l'oeil (ouvert / barré)
                        if (type === 'text') {
                            eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
                        } else {
                            eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
                        }
                    });
                </script>
            </div>
            
            <footer class="login-footer">
                &copy; {{ date('Y') }} Mikem Technologie. Tous droits réservés. <br>
                Développé par <strong>Vianney SASSE & Noel GBAGUIDI</strong>
            </footer>
        </div>
    </div>
</body>
</html>
