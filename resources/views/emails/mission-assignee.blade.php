<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #1a1a2e; max-width: 600px; margin: 0 auto; background-color: #f4f6f9; padding: 20px; }
        .email-container { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background: linear-gradient(135deg, #0f3460, #195eac); color: white; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: bold; letter-spacing: 1px; }
        .header p { margin: 5px 0 0; opacity: 0.9; font-size: 14px; }
        .content { padding: 30px 25px; }
        .mission-details { background: #f8fafc; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #195eac; }
        .mission-details p { margin: 8px 0; font-size: 15px; }
        .label { font-weight: 600; color: #64748b; display: inline-block; width: 100px; }
        .value { font-weight: bold; color: #0f3460; }
        .btn { display: inline-block; background-color: #195eac; color: white !important; text-decoration: none; padding: 14px 28px; border-radius: 6px; font-weight: bold; margin-top: 15px; text-align: center; }
        .footer { background: #eef2f6; color: #64748b; padding: 20px; text-align: center; font-size: 13px; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <!-- Le logo sera chargé via l'URL complète -->
            <div style="background: white; display: inline-block; padding: 10px 20px; border-radius: 8px; margin-bottom: 15px;">
                <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="SAV MIKEM" style="max-height: 40px; display: block;">
            </div>
            <p style="margin: 0; font-size: 16px; font-weight: bold;">Nouvelle intervention requise</p>
        </div>
        <div class="content">
            <p style="font-size: 16px;">Bonjour <strong style="color: #0f3460;">{{ $technicien->name }}</strong>,</p>
            <p style="color: #475569;">Une nouvelle mission vient de vous être assignée depuis la plateforme de gestion centralisée :</p>
            
            <div class="mission-details">
                <p><span class="label">Mission :</span> <span class="value">{{ $mission->titre }}</span></p>
                <p><span class="label">Type :</span> <span class="value">{{ $mission->type_mission }}</span></p>
                <p><span class="label">Date :</span> <span class="value">{{ $mission->date_mission->format('d/m/Y') }}</span></p>
                <p><span class="label">Lieu :</span> <span class="value">{{ $mission->adresse }}</span></p>
                <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 15px 0;">
                <p><span class="label">Détails :</span> <br><span style="color: #475569; font-size: 14px;">{{ Str::limit($mission->description, 200) }}</span></p>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <!-- Le lien utilise la variable APP_URL de ton fichier .env automatiquement ! -->
                <a href="{{ config('app.url') }}/login" class="btn">Consulter la Plateforme</a>
            </div>
        </div>
        <div class="footer">
            <p>Cet email est automatique, merci de ne pas y répondre.</p>
            <p>© {{ date('Y') }} MIKEM TECHNOLOGIES</p>
        </div>
    </div>
</body>
</html>
