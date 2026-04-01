<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #6c63ff, #e94560); color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 25px; border: 1px solid #e0e0e0; }
        .mission-details { background: white; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #6c63ff; }
        .label { font-weight: bold; color: #6c63ff; }
        .footer { background: #2d2d2d; color: #aaa; padding: 15px; text-align: center; font-size: 12px; border-radius: 0 0 8px 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔧 SAV Mikem</h1>
        <p>Nouvelle mission assignée</p>
    </div>
    <div class="content">
        <p>Bonjour <strong>{{ $technicien->name }}</strong>,</p>
        <p>Une nouvelle mission vous a été assignée :</p>
        <div class="mission-details">
            <p><span class="label">Titre :</span> {{ $mission->titre }}</p>
            <p><span class="label">Type :</span> {{ $mission->type_mission }}</p>
            <p><span class="label">Date :</span> {{ $mission->date_mission->format('d/m/Y') }}</p>
            <p><span class="label">Adresse :</span> {{ $mission->adresse }}</p>
            <p><span class="label">Description :</span> {{ Str::limit($mission->description, 200) }}</p>
            @if($mission->prix_deplacement)
            <p><span class="label">Prix déplacement :</span> {{ number_format($mission->prix_deplacement, 0, ',', ' ') }} Fcfa</p>
            @endif
        </div>
        <p>Connectez-vous à l'application pour gérer cette mission.</p>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} SAV Mikem</p>
    </div>
</body>
</html>
