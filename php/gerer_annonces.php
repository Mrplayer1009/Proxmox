<?php
session_start();
include 'db.php';

if (!isset($_SESSION['is_livreur']) != 1) {
    header("Location: ../index.php");
    exit();
}

$livreur_id = $_SESSION['user_id'];

// Récupérer les annonces via l'API
$response = $api_client->get('annonces', ['livreur_id' => $livreur_id]);
$annonces = $response['data'] ?? [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer mes annonces</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<?php
include 'header.php';
?>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <nav class="text-blue-400 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Gestion des Annonces</h1>
        </div>
    </nav>

    <div class="container mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Mes annonces</h2>

        <div class="mb-4">
            <a href="ajouter_annonce.php" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                Ajouter une annonce
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="border border-gray-300 px-4 py-2">Titre</th>
                        <th class="border border-gray-300 px-4 py-2">Description</th>
                        <th class="border border-gray-300 px-4 py-2">Statut</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($annonces as $annonce) : ?>
                        <tr class="text-center bg-gray-100">
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($annonce['titre']) ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($annonce['description']) ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($annonce['statut']) ?></td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="modifier_annonce.php?id=<?= $annonce['id'] ?>" class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600">Modifier</a>
                                <a href="supprimer_annonce.php?id=<?= $annonce['id'] ?>" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
