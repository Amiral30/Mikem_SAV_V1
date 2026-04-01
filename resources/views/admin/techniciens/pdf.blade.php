<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Technicien - {{ $technicien->name }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #e02424; padding-bottom: 20px; }
        .header h1 { margin: 0; color: #1e3a8a; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; color: #666; font-size: 12px; }
        .info-section { margin-bottom: 30px; }
        .info-table { border: none; width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; vertical-align: top; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th { background: #1e3a8a; color: white; padding: 10px;text-align: left; font-size: 12px; text-transform: uppercase; }
        .table td { padding: 10px; border-bottom: 1px solid #ddd; font-size: 13px; }
        .table tr:nth-child(even) { background-color: #f9fafb; }
        .total-box { margin-top: 30px; text-align: right; background: #f8fafc; padding: 15px; border-radius: 4px; border: 1px solid #cbd5e1; }
        .total-box h3 { margin: 0; font-size: 18px; color: #e02424; }
        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #9ca3af; border-top: 1px solid #eee; padding-top: 10px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>MIKEM TECHNOLOGIE</h1>
        <p>Bilan Financier des Interventions SAV</p>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td style="width: 60%;">
                    <strong>Technicien :</strong> {{ $technicien->name }}<br>
                    <strong>Email :</strong> {{ $technicien->email }}<br>
                    <strong>Téléphone :</strong> {{ $technicien->telephone ?? 'N/A' }}
                </td>
                <td style="width: 40%; text-align: right;">
                    <strong>Date du rapport :</strong> {{ date('d/m/Y') }}<br>
                    <strong>Missions effectuées :</strong> {{ $technicien->missions->count() }}
                </td>
            </tr>
        </table>
    </div>

    <h3>Historique des Missions</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Mission</th>
                <th>Statut</th>
                <th class="text-right">Frais Déplacement (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($technicien->missions as $mission)
            <tr>
                <td>{{ $mission->date_mission->format('d/m/Y') }}</td>
                <td>{{ Str::limit($mission->titre, 40) }}</td>
                <td>{{ $mission->statut_label }}</td>
                <td class="text-right">{{ number_format($mission->prix_deplacement, 0, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        TOTAL FRAIS DE DÉPLACEMENT: <br>
        <h3>{{ number_format($totalDeplacement, 0, ',', ' ') }} FCFA</h3>
    </div>

    <div class="footer">
        Généré depuis l'application SAV Mikem le {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>
