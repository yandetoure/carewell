<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Prescription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .logo img {
            display: block;
            margin: 0 auto 20px auto;
            width: 150px; /* Ajustez la taille du logo selon vos besoins */
        }
        h4 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }
        h3 {
            color: #333;
            margin: 15px 0;
        }
        p {
            color: #555;
            margin: 10px 0;
            text-align: justify; /* Pour un meilleur alignement du texte */
        }
        footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h4>Nouvelle Prescription</h4>
        <!-- <img src="{{ asset('images/wellogo.png') }}" alt="Welcome Image" style="width: 100%; height: auto; margin-bottom: 20px;"> -->
        <h3>Bonjour, {{ $first_name }} {{ $last_name }}</h3>
        <p>Votre medecin vous a fait une nouvelle prescription, veuillez verifier votre ticket sur l'application.</p>
        <!-- <h3>Numéro de dossier : {{ $identification_number }}</h3> -->
        <p>Prenez bien soin de vous !</p>
        <footer>
            <p>{{ config('app.name') }}</p>
        </footer>
    </div>
</body>
</html>
