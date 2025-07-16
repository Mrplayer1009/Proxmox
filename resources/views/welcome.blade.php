<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Ecodeli</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f8f8; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 60px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 12px #0002; padding: 2.5rem; text-align: center; }
        h1 { color: #f53003; margin-bottom: 1rem; }
        p { color: #444; font-size: 1.1rem; margin-bottom: 2rem; }
        a.button { display: inline-block; margin: 0 1rem; background: #f53003; color: #fff; padding: 0.8rem 2rem; border-radius: 5px; text-decoration: none; font-weight: bold; transition: background 0.2s; }
        a.button:hover { background: #c41c00; }
        .logo { margin-bottom: 2rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="https://cdn-icons-png.flaticon.com/512/2909/2909765.png" alt="Logo Ecodeli" width="90" />
        </div>
        <h1>Bienvenue sur Ecodeli</h1>
        <p>Ici soyez ecologique et collaborer avec d'autres personne</p>
        <a href="{{ route('login') }}" class="button">Se connecter</a>
        <a href="{{ route('register') }}" class="button">Créer un compte</a>
    </div>
</body>
</html>
