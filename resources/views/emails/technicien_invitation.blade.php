<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue chez Mikem Technologie</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f7fa; margin: 0; padding: 0; color: #334155; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .header { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 40px 20px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.025em; }
        .content { padding: 40px; line-height: 1.6; }
        .welcome-text { font-size: 18px; color: #0f172a; margin-bottom: 24px; }
        .credentials-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin: 24px 0; }
        .credential-item { margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center; }
        .credential-label { font-size: 13px; font-weight: 600; text-transform: uppercase; color: #64748b; }
        .credential-value { font-family: 'Courier New', monospace; font-weight: 700; color: #0f172a; font-size: 16px; }
        .code-display { background: #6c63ff; color: #ffffff; font-size: 32px; font-weight: 800; text-align: center; padding: 15px; border-radius: 8px; margin: 20px 0; letter-spacing: 12px; padding-left: 27px; }
        .action-button { display: block; background: #6c63ff; color: #ffffff; text-align: center; padding: 16px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; margin-top: 32px; transition: background 0.3s; }
        .footer { padding: 30px; text-align: center; font-size: 13px; color: #94a3b8; border-top: 1px solid #f1f5f9; }
        .warning-text { font-size: 12px; color: #ef4444; margin-top: 20px; text-align: center; font-style: italic; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>MIKEM TECHNOLOGIE</h1>
        </div>
        <div class="content">
            <p class="welcome-text">Bonjour {{ $user->name }},</p>
            <p>Bienvenue au sein de l'équipe Mikem Technologie. Votre espace technicien a été configuré avec succès.</p>
            
            <p>Voici vos identifiants de connexion provisoires :</p>
            
            <div class="credentials-box">
                <div class="credential-item">
                    <span class="credential-label">E-mail</span>
                    <span class="credential-value">{{ $user->email }}</span>
                </div>
                <div class="credential-item">
                    <span class="credential-label">Mot de passe par défaut</span>
                    <span class="credential-value">{{ $password }}</span>
                </div>
            </div>

            <p>Pour des raisons de sécurité, vous devez valider votre compte à l'aide du code suivant lors de votre première connexion :</p>
            
            <div class="code-display">
                {{ $user->verification_code }}
            </div>

            <p class="warning-text">Après avoir saisi ce code, vous devrez obligatoirement définir votre propre mot de passe personnel.</p>

            <a href="{{ route('login') }}" class="action-button">Accéder à mon espace</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Mikem Technologie. Tous droits réservés.<br>
            Ceci est un message automatique, merci de ne pas y répondre.
        </div>
    </div>
</body>
</html>
