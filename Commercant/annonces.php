<?php
session_start();
require_once '../php/verif.php';
require_once '../php/api_client.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'commercant') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$api_client = new ApiClient();

$response = $api_client->get("annonce?user_id=$user_id");
$announcements = json_decode($response, true);

if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $api_client->delete("annonce/$delete_id");
    header('Location: annonces.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des annonces - EcoDeli</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include 'header_commercant.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Gestion des annonces</h1>
        
        <div class="mb-6">
            <a href="ajouter_annonce.php" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                Ajouter une annonce
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <?php if (empty($announcements)): ?>
                <div class="p-6 text-center text-gray-500">
                    Vous n'avez pas encore d'annonces.
                </div>
            <?php else: ?>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($announcements as $announcement): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($announcement['titre']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($announcement['description']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($announcement['date_creation']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="modifier_annonce.php?id=<?= $announcement['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</a>
                                    <form method="post" class="inline">
                                        <input type="hidden" name="delete_id" value="<?= $announcement['id'] ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce?')">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
