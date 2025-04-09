<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification de votre adresse e-mail</title>
    <style>
        body {
            background-color: #f9fafb;
            font-family: 'Segoe UI', sans-serif;
            color: #333;
            padding: 20px;
        }
        .email-container {
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #0d6efd;
        }
        .btn {
            display: inline-block;
            background: #0d6efd;
            color: #fff !important;
            padding: 12px 20px;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 0.9em;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>Bienvenue sur Maison Connectée !</h1>
        <p>Merci de vous être inscrit sur notre plateforme.</p>
        <p>Bonjour {{ $user->username ?? $user->name }},</p>
        <p>Pour activer votre compte et profiter pleinement des services connectés, veuillez confirmer votre adresse e-mail en cliquant sur le bouton ci-dessous :</p>
        
        <a href="{{ $verificationUrl }}" class="btn">Vérifier mon adresse e-mail</a>

        <p class="footer">Si vous n'avez pas créé de compte, vous pouvez ignorer cet e-mail.</p>
    </div>
</body>
</html>
