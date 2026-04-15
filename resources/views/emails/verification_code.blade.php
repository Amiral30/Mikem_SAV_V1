<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre code de vérification</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f7fa; color: #334155; margin: 0; padding: 0; }
        .container { max-width: 500px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .header { background: #0f172a; padding: 30px; text-align: center; color: #ffffff; }
        .content { padding: 40px; text-align: center; }
        .code-display { background: #f8fafc; border: 2px dashed #6c63ff; color: #6c63ff; font-size: 36px; font-weight: 800; padding: 20px; border-radius: 8px; margin: 30px 0; letter-spacing: 10px; padding-left: 30px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; background: #f8fafc; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="background: white; display: inline-block; padding: 10px 15px; border-radius: 6px; margin-bottom: 10px;">
                <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="MIKEM" style="max-height: 30px; display: block;">
            </div>
            <h2 style="margin:0; font-size: 18px;">Vérification de compte</h2>
        </div>
        <div class="content">
            <p>Bonjour {{ $user->name }},</p>
            <p>Voici votre nouveau code de vérification pour accéder à votre espace Mikem Technologie.</p>
            
            <div class="code-display">
                {{ $code }}
            </div>

            <p style="font-size: 0.9rem; color: #64748b;">Ce code est personnel. Ne le partagez avec personne.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Mikem Technologie.
        </div>
    </div>
</body>
</html>
