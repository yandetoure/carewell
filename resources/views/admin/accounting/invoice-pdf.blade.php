<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Facture - {{ $month }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #2563eb;
            margin: 0;
        }

        .info {
            margin-bottom: 20px;
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
            background-color: #2563eb;
            color: white;
        }

        .total {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            padding: 10px;
            background-color: #f0f0f0;
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
        <h1>FACTURE</h1>
        @if($clinic)
            <h2>{{ $clinic->name }}</h2>
            <p>{{ $clinic->address }}</p>
            <p>Tél: {{ $clinic->phone_number }}</p>
        @else
            <h2>CareWell</h2>
        @endif
    </div>

    <div class="info">
        <p><strong>Période:</strong> {{ $month }}</p>
        <p><strong>Date d'émission:</strong> {{ now()->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Patient</th>
                <th>Service</th>
                <th>Médecin</th>
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
                    <td>{{ $appointment->doctor ? 'Dr. ' . $appointment->doctor->first_name . ' ' . $appointment->doctor->last_name : 'N/A' }}
                    </td>
                    <td>{{ number_format($appointment->service ? $appointment->service->price : 0, 0, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        TOTAL: {{ number_format($total, 0, ',', ' ') }} FCFA
    </div>

    <div class="footer">
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>

</html>