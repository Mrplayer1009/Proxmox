<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'];

$response = $api_client->get('users', ['id' => $user_id]);
$livreur = $response['data'] ?? [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Livreur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold text-center text-blue-600">Espace Livreur</h1>
        <p class="text-center text-gray-700">Bienvenue, <strong><?= htmlspecialchars($livreur['prenom']) . ' ' . htmlspecialchars($livreur['nom']) ?></strong></p>

        <div class="mt-6 space-y-4">
            <a href="annonces.php" class="block p-4 bg-blue-500 text-white text-center rounded-lg shadow-md hover:bg-blue-600 transition">
                📢 Gérer mes annonces
            </a>
            <a href="documents.php" class="block p-4 bg-green-500 text-white text-center rounded-lg shadow-md hover:bg-green-600 transition">
                📄 Gérer mes pièces justificatives
            </a>
            <a href="livraisons.php" class="block p-4 bg-yellow-500 text-white text-center rounded-lg shadow-md hover:bg-yellow-600 transition">
                🚚 Gérer mes livraisons
            </a>
            <a href="paiements.php" class="block p-4 bg-red-500 text-white text-center rounded-lg shadow-md hover:bg-red-600 transition">
                💰 Gérer mes paiements
            </a>
            <a href="planning.php" class="block p-4 bg-purple-500 text-white text-center rounded-lg shadow-md hover:bg-purple-600 transition">
                📅 Gérer mon planning et déplacements
            </a>
        </div>
        
        <div class="mt-6 text-center">
            <a href="deconnexion.php" class="text-gray-500 hover:text-red-600">Se déconnecter</a>
        </div>
    </div>
</body>
</html>
