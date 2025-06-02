<?php
session_start();
require_once '../php/verif.php';
require_once '../php/api_client.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'prestataire') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$api_client = new ApiClient();

if (!isset($_GET['id'])) {
    header('Location: interventions.php');
    exit;
}

$intervention_id = $_GET['id'];

$response = $api_client->get("interventions/$intervention_id");
$intervention = json_decode($response, true);

if (!$intervention || $intervention['prestataire_id'] != $user_id) {
    header('Location: interventions.php');
    exit;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update_status') {
        $new_status = $_POST['new_status'];
        
        $update_data = [
            'statut' => $new_status
        ];
        
        // Add completion details if needed
        if ($new_status === 'completed') {
            $update_data['commentaire_prestataire'] = $_POST['commentaire'] ?? '';
            $update_data['date_fin'] = date('Y-m-d H:i:s');
        }
        
        $response = $api_client->put("interventions/$intervention_id", $update_data);
        $result = json_decode($response, true);
        
        if (isset($result['status']) && $result['status'] === 'success') {
            $message = "Statut de l'intervention mis à jour avec succès.";
            
            // Refresh intervention data
            $response = $api_client->get("interventions/$intervention_id");
            $intervention = json_decode($response, true);
        } else {
            $error = $result['message'] ?? "Erreur lors de la mise à jour du statut.";
        }
    }
}

// Format status text
$status_text = '';
$status_class = '';

switch ($intervention['statut']) {
    case 'pending':
        $status_text = 'En attente';
        $status_class = 'bg-yellow-100 text-yellow-800';
        break;
    case 'in_progress':
        $status_text = 'En cours';
        $status_class = 'bg-blue-100 text-blue-800';
        break;
    case 'completed':
        $status_text = 'Terminé';
        $status_class = 'bg-green-100 text-green-800';
        break;
    case 'cancelled':
        $status_text = 'Annulé';
        $status_class = 'bg-red-100 text-red-800';
        break;
    default:
        $status_text = 'Inconnu';
        $status_class = 'bg-gray-100 text-gray-800';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'intervention - EcoDeli</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include 'header_prestataire.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold">Détails de l'intervention</h1>
            <a href="interventions.php" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300">
                &larr; Retour aux interventions
            </a>
        </div>
        
        <?php if (!empty($message)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p><?= $message ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p><?= $error ?></p>
            </div>
        <?php endif; ?>
        
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <!-- Intervention Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex flex-wrap items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold"><?= htmlspecialchars($intervention['titre'] ?? 'Intervention') ?></h2>
                        <p class="text-gray-500">ID: <?= $intervention['id'] ?></p>
                    </div>
                    <div>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full <?= $status_class ?>">
                            <?= $status_text ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Intervention Details -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Informations générales</h3>
                        
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500">Date</p>
                            <p class="mt-1"><?= date('d/m/Y', strtotime($intervention['date_intervention'])) ?></p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500">Heure</p>
                            <p class="mt-1"><?= date('H:i', strtotime($intervention['heure_intervention'] ?? $intervention['heure_debut'])) ?></p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500">Type de service</p>
                            <p class="mt-1"><?= htmlspecialchars($intervention['service_nom'] ?? 'Non spécifié') ?></p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500">Description</p>
                            <p class="mt-1"><?= nl2br(htmlspecialchars($intervention['description'] ?? 'Aucune description fournie')) ?></p>
                        </div>
                        
                        <?php if ($intervention['statut'] === 'completed' && !empty($intervention['commentaire_prestataire'])): ?>
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Commentaire du prestataire</p>
                                <p class="mt-1"><?= nl2br(htmlspecialchars($intervention['commentaire_prestataire'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Right Column -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Informations client</h3>
                        
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500">Nom</p>
                            <p class="mt-1"><?= htmlspecialchars($intervention['client_nom'] ?? 'Non spécifié') ?></p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500">Adresse</p>
                            <p class="mt-1"><?= htmlspecialchars($intervention['adresse'] ?? 'Non spécifiée') ?></p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500">Téléphone</p>
                            <p class="mt-1"><?= htmlspecialchars($intervention['telephone'] ?? 'Non spécifié') ?></p>
                        </div>
                        
                        <?php if (!empty($intervention['instructions_speciales'])): ?>
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Instructions spéciales</p>
                                <p class="mt-1"><?= nl2br(htmlspecialchars($intervention['instructions_speciales'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <div class="flex flex-wrap justify-end space-x-3">
                        <?php if ($intervention['statut'] === 'pending'): ?>
                            <form method="post">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="new_status" value="in_progress">
                                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Démarrer l'intervention
                                </button>
                            </form>
                        <?php endif; ?>
                        
                        <?php if ($intervention['statut'] === 'in_progress'): ?>
                            <button type="button" onclick="showCompleteModal()" class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Terminer l'intervention
                            </button>
                        <?php endif; ?>
                        
                        <?php if ($intervention['statut'] === 'pending'): ?>
                            <form method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette intervention ?');">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="new_status" value="cancelled">
                                <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    Annuler l'intervention
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal for completing intervention -->
    <div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <h3 class="text-xl font-semibold mb-4">Terminer l'intervention</h3>
            <form method="post">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="new_status" value="completed">
                
                <div class="mb-4">
                    <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-1">Commentaire (optionnel)</label>
                    <textarea id="commentaire" name="commentaire" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideCompleteModal()" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Annuler
                    </button>
                    <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Terminer l'intervention
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function showCompleteModal() {
            document.getElementById('completeModal').classList.remove('hidden');
        }
        
        function hideCompleteModal() {
            document.getElementById('completeModal').classList.add('hidden');
        }
    </script>
</body>
</html>
