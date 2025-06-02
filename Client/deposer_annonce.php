<?php
session_start();
require_once '../php/verif.php';
require_once '../php/api_client.php';

// Verify if user is logged in and has the client role
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'client') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$api_client = new ApiClient();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $type_service = $_POST['type_service'] ?? '';
    $date_souhaitee = $_POST['date_souhaitee'] ?? '';
    
    if (empty($titre) || empty($description) || empty($type_service) || empty($date_souhaitee)) {
        $error = 'Tous les champs sont obligatoires.';
    } else {
        $data = [
            'titre' => $titre,
            'description' => $description,
            'user_id' => $user_id,
            'type' => 'client',
            'type_service' => $type_service,
            'date_souhaitee' => $date_souhaitee
        ];
        
        $response = $api_client->post('annonce', $data);
        $result = json_decode($response, true);
        
        if (isset($result['success']) && $result['success']) {
            $success = 'Annonce déposée avec succès! Vous serez notifié lorsqu\'un prestataire ou livreur répondra à votre annonce.';
        } else {
            $error = 'Une erreur est survenue lors du dépôt de l\'annonce.';
        }
    }
}

// Get service types
$response = $api_client->get("service");
$services = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déposer une annonce - EcoDeli</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include 'header_client.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Déposer une annonce</h1>
        
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= $error ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= $success ?>
                <p class="mt-2">
                    <a href="annonces.php" class="underline">Voir mes annonces</a>
                </p>
            </div>
        <?php endif; ?>
        
        <div class="bg-white shadow-md rounded-lg p-6">
            <form method="post">
                <div class="mb-4">
                    <label for="titre" class="block text-gray-700 text-sm font-bold mb-2">Titre de l'annonce</label>
                    <input type="text" id="titre" name="titre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description détaillée</label>
                    <textarea id="description" name="description" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="type_service" class="block text-gray-700 text-sm font-bold mb-2">Type de service</label>
                    <select id="type_service" name="type_service" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Sélectionnez un type de service</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= $service['id'] ?>"><?= htmlspecialchars($service['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-6">
                    <label for="date_souhaitee" class="block text-gray-700 text-sm font-bold mb-2">Date souhaitée</label>
                    <input type="date" id="date_souhaitee" name="date_souhaitee" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required min="<?= date('Y-m-d') ?>">
                </div>
                
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Déposer l'annonce
                    </button>
                    <a href="dashboard.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
