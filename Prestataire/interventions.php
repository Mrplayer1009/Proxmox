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

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update_status') {
        $intervention_id = $_POST['intervention_id'];
        $new_status = $_POST['new_status'];
        
        $update_data = [
            'statut' => $new_status
        ];
        
        if ($new_status === 'completed') {
            $update_data['commentaire_prestataire'] = $_POST['commentaire'] ?? '';
            $update_data['date_fin'] = date('Y-m-d H:i:s');
        }
        
        $response = $api_client->put("interventions/$intervention_id", $update_data);
        $result = json_decode($response, true);
        
        if (isset($result['status']) && $result['status'] === 'success') {
            $message = "Statut de l'intervention mis à jour avec succès.";
        } else {
            $error = $result['message'] ?? "Erreur lors de la mise à jour du statut.";
        }
    }
}

// Get filter parameters
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$date_filter = isset($_GET['date']) ? $_GET['date'] : 'upcoming';

// Build API query
$api_query = "interventions?prestataire_id=$user_id";

if ($status_filter !== 'all') {
    $api_query .= "&status=$status_filter";
}

if ($date_filter === 'upcoming') {
    $api_query .= "&date_from=" . date('Y-m-d');
} elseif ($date_filter === 'past') {
    $api_query .= "&date_to=" . date('Y-m-d');
} elseif ($date_filter === 'today') {
    $api_query .= "&date=" . date('Y-m-d');
}

// Get interventions
$response = $api_client->get($api_query);
$interventions = json_decode($response, true) ?? [];

// Sort interventions by date
usort($interventions, function($a, $b) {
    return strtotime($a['date_intervention']) - strtotime($b['date_intervention']);
});
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Interventions - EcoDeli</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include 'header_prestataire.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Mes Interventions</h1>
        
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
        
        <!-- Filters -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Filtres</h2>
            <form method="get" class="flex flex-wrap gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select id="status" name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>Tous</option>
                        <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>En attente</option>
                        <option value="in_progress" <?= $status_filter === 'in_progress' ? 'selected' : '' ?>>En cours</option>
                        <option value="completed" <?= $status_filter === 'completed' ? 'selected' : '' ?>>Terminé</option>
                        <option value="cancelled" <?= $status_filter === 'cancelled' ? 'selected' : '' ?>>Annulé</option>
                    </select>
                </div>
                
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <select id="date" name="date" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="all" <?= $date_filter === 'all' ? 'selected' : '' ?>>Toutes les dates</option>
                        <option value="upcoming" <?= $date_filter === 'upcoming' ? 'selected' : '' ?>>À venir</option>
                        <option value="past" <?= $date_filter === 'past' ? 'selected' : '' ?>>Passées</option>
                        <option value="today" <?= $date_filter === 'today' ? 'selected' : '' ?>>Aujourd'hui</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Interventions List -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Liste des interventions</h2>
            
            <?php if (empty($interventions)): ?>
                <p class="text-gray-500 text-center py-8">Aucune intervention trouvée avec les filtres sélectionnés</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($interventions as $intervention): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('d/m/Y', strtotime($intervention['date_intervention'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('H:i', strtotime($intervention['heure_intervention'] ?? $intervention['heure_debut'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($intervention['client_nom'] ?? 'Client') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($intervention['service_nom'] ?? 'Service') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                        $status_class = '';
                                        $status_text = '';
                                        
                                        switch ($intervention['statut']) {
                                            case 'pending':
                                                $status_class = 'bg-yellow-100 text-yellow-800';
                                                $status_text = 'En attente';
                                                break;
                                            case 'in_progress':
                                                $status_class = 'bg-blue-100 text-blue-800';
                                                $status_text = 'En cours';
                                                break;
                                            case 'completed':
                                                $status_class = 'bg-green-100 text-green-800';
                                                $status_text = 'Terminé';
                                                break;
                                            case 'cancelled':
                                                $status_class = 'bg-red-100 text-red-800';
                                                $status_text = 'Annulé';
                                                break;
                                            default:
                                                $status_class = 'bg-gray-100 text-gray-800';
                                                $status_text = 'Inconnu';
                                        }
                                        ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $status_class ?>">
                                            <?= $status_text ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="voir_intervention.php?id=<?= $intervention['id'] ?>" class="text-blue-600 hover:text-blue-900 mr-3">Détails</a>
                                        
                                        <?php if ($intervention['statut'] === 'pending'): ?>
                                            <button type="button" onclick="updateStatus(<?= $intervention['id'] ?>, 'in_progress')" class="text-green-600 hover:text-green-900 mr-3">
                                                Démarrer
                                            </button>
                                        <?php endif; ?>
                                        
                                        <?php if ($intervention['statut'] === 'in_progress'): ?>
                                            <button type="button" onclick="completeIntervention(<?= $intervention['id'] ?>)" class="text-green-600 hover:text-green-900">
                                                Terminer
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Modal for completing intervention -->
    <div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <h3 class="text-xl font-semibold mb-4">Terminer l'intervention</h3>
            <form id="completeForm" method="post">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" id="intervention_id" name="intervention_id" value="">
                <input type="hidden" name="new_status" value="completed">
                
                <div class="mb-4">
                    <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-1">Commentaire (optionnel)</label>
                    <textarea id="commentaire" name="commentaire" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
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
        function updateStatus(interventionId, newStatus) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'update_status';
            
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'intervention_id';
            idInput.value = interventionId;
            
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'new_status';
            statusInput.value = newStatus;
            
            form.appendChild(actionInput);
            form.appendChild(idInput);
            form.appendChild(statusInput);
            
            document.body.appendChild(form);
            form.submit();
        }
        
        function completeIntervention(interventionId) {
            document.getElementById('intervention_id').value = interventionId;
            document.getElementById('completeModal').classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('completeModal').classList.add('hidden');
        }
    </script>
</body>
</html>
