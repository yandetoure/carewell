<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rapport Financier - {{ $month }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #10b981;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #10b981;
            margin: 0;
        }

        .summary {
            display: flex;
            justify-content: space-around;
            margin: 30px 0;
        }

        .summary-card {
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 30%;
        }

        .summary-card h3 {
            margin: 0;
            color: #666;
            font-size: 14px;
        }

        .summary-card p {
            margin: 10px 0 0 0;
            font-size: 24px;
            font-weight: bold;
            color: #10b981;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #10b981;
            color: white;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>RAPPORT FINANCIER</h1>
        @if($clinic)
            <h2>{{ $clinic->name }}</h2>
            <p>{{ $clinic->address }}</p>
        @else
            <h2>CareWell</h2>
        @endif
        <p><strong>Période:</strong> {{ $month }}</p>
    </div>

    <div class="summary" style="display: table; width: 100%; margin: 30px 0;">
        <div class="summary-card"
            style="display: table-cell; width: 33%; padding: 15px; border: 1px solid #ddd; text-align: center;">
            <h3 style="margin: 0; color: #666; font-size: 14px;">Revenus du mois</h3>
            <p style="margin: 10px 0 0 0; font-size: 20px; font-weight: bold; color: #10b981;">
                {{ number_format($monthlyRevenue, 0, ',', ' ') }} FCFA</p>
        </div>
        <div class="summary-card"
            style="display: table-cell; width: 33%; padding: 15px; border: 1px solid #ddd; text-align: center;">
            <h3 style="margin: 0; color: #666; font-size: 14px;">Revenus totaux</h3>
            <p style="margin: 10px 0 0 0; font-size: 20px; font-weight: bold; color: #10b981;">
                {{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</p>
        </div>
        <div class="summary-card"
            style="display: table-cell; width: 33%; padding: 15px; border: 1px solid #ddd; text-align: center;">
            <h3 style="margin: 0; color: #666; font-size: 14px;">Rendez-vous</h3>
            <p style="margin: 10px 0 0 0; font-size: 20px; font-weight: bold; color: #10b981;">
                {{ $appointments->count() }}</p>
        </div>
    </div>

    <h2 style="color: #10b981; margin-top: 40px;">Détails des rendez-vous</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Patient</th>
                <th>Service</th>
                <th>Statut</th>
                <th>Prix (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->appointment_date ? $appointment->appointment_date->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $appointment->user ? $appointment->user->first_name . ' ' . $appointment->user->last_name : 'N/A' }}
                    </td>
                    <td>{{ $appointment->service ? $appointment->service->name : 'N/A' }}</td>
                    <td>{{ ucfirst($appointment->status) }}</td>
                    <td>{{ number_format($appointment->service ? $appointment->service->price : 0, 0, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Ce document est confidentiel et destiné uniquement à un usage interne</p>
    </div>
</body>

</html>