<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
<nav class="bg-blue-600 text-white p-4 flex items-center">
    <div class="flex items-center space-x-2">
        <img src="src/logo.png" alt="EcoDeli" class="h-16 w-auto"> 
        <div class="text-2xl font-bold">EcoDeli</div>
        <div>
            <a href="php/register.php" class="mx-2 hover:underline">Inscription</a>
            <a href="php/login.php" class="mx-2 hover:underline">Connexion</a>
            <a href="php/backoffice.php" class="mx-2 hover:underline">Back-office</a>
        </div>
    </nav>
    <div class="container mx-auto p-8 text-center">
        <h1 class="text-3xl font-bold">Bienvenue sur notre site</h1>
        <p class="mt-4 text-gray-700">Inscrivez-vous, connectez-vous et gérez vos accès facilement.</p>
    </div>

    <?php include 'php/footer.php'; ?>
</body>
</html>