<?php
session_start();
require_once '../php/verif.php';
require_once '../php/api_client.php';

if (!isset($_SESSION['user_id']) || !in_array('livreur', $_SESSION['roles'])) {
    header('Location: ../index.php');
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_availability') {
    $data = [
        'user_id' => $userId,
        'date_debut' => $_POST['date_debut'],
        'date_fin' => $_POST['date_fin'],
        'lieu_depart' => $_POST['lieu_depart'],
        'lieu_arrivee' => $_POST['lieu_arrivee'],
        'statut' => 'available'
    ];
    
    $result = apiRequest("POST", "planning", $data);
    
    if ($result && isset($result['id'])) {
        $successMessage = "Disponibilité ajoutée avec succès.";
    } else {
        $errorMessage = "Erreur lors de l'ajout de la disponibilité.";
    }
}

// Handle delete request
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $planningId = $_GET['delete'];
    $result = apiRequest("DELETE", "planning/$planningId", null);
    
    if ($result && isset($result['success']) && $result['success']) {
        $successMessage = "Disponibilité supprimée avec succès.";
    } else {
        $errorMessage = "Erreur lors de la suppression de la disponibilité.";
    }
}

// Get planning data from API
$planning = apiRequest("GET", "planning?user_id=$userId", null);

// Get upcoming deliveries
$upcomingDeliveries = apiRequest("GET", "intervention?user_id=$userId&status=pending,in_progress", null);

include 'header_livreur.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Mon Planning</h1>
    
    <?php if (isset($successMessage)): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p><?= $successMessage ?></p>
        </div>
    <?php endif; ?>
    
    <?php if (isset($errorMessage)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p><?= $errorMessage ?></p>
        </div>
    <?php endif; ?>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Upcoming Deliveries -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Livraisons à venir</h2>
            
            <?php if (empty($upcomingDeliveries)): ?>
                <p class="text-gray-500">Aucune livraison à venir.</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($upcomingDeliveries as $delivery): ?>
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold"><?= date('d/m/Y', strtotime($delivery['date'])) ?></p>
                                    <p class="text-sm text-gray-600"><?= htmlspecialchars($delivery['adresse']) ?></p>
                                    <p class="text-sm text-gray-600">Type: <?= htmlspecialchars($delivery['type']) ?></p>
                                </div>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $delivery['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' ?>">
                                    <?= $delivery['status'] === 'pending' ? 'En attente' : 'En cours' ?>
                                </span>
                            </div>
                            <a href="livraisons.php?id=<?= $delivery['id'] ?>" class="text-blue-600 hover:underline text-sm">Voir détails</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Add Availability Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Ajouter une disponibilité</h2>
            
            <form method="POST" action="">
                <input type="hidden" name="action" value="add_availability">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="date_debut" class="block text-gray-700 text-sm font-bold mb-2">Date de début</label>
                        <input type="datetime-local" id="date_debut" name="date_debut" required 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    
                    <div>
                        <label for="date_fin" class="block text-gray-700 text-sm font-bold mb-2">Date de fin</label>
                        <input type="datetime-local" id="date_fin" name="date_fin" required 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="lieu_depart" class="block text-gray-700 text-sm font-bold mb-2">Lieu de départ</label>
                        <input type="text" id="lieu_depart" name="lieu_depart" required 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    
                    <div>
                        <label for="lieu_arrivee" class="block text-gray-700 text-sm font-bold mb-2">Lieu d'arrivée</label>
                        <input type="text" id="lieu_arrivee" name="lieu_arrivee" required 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>
                
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Ajouter la disponibilité
                </button>
            </form>
        </div>
    </div>
    
    <!-- Availability Calendar -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Mes disponibilités</h2>
        
        <?php if (empty($planning)): ?>
            <p class="text-gray-500">Aucune disponibilité enregistrée.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date de début</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date de fin</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lieu de départ</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lieu d'arrivée</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($planning as $availability): ?>
                            <tr>
                                <td class="py-3 px-4 border-b border-gray-200"><?= date('d/m/Y H:i', strtotime($availability['date_debut'])) ?></td>
                                <td class="py-3 px-4 border-b border-gray-200"><?= date('d/m/Y H:i', strtotime($availability['date_fin'])) ?></td>
                                <td class="py-3 px-4 border-b border-gray-200"><?= htmlspecialchars($availability['lieu_depart']) ?></td>
                                <td class="py-3 px-4 border-b border-gray-200"><?= htmlspecialchars($availability['lieu_arrivee']) ?></td>
                                <td class="py-3 px-4 border-b border-gray-200">
                                    <?php if ($availability['statut'] === 'available'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Disponible
                                        </span>
                                    <?php elseif ($availability['statut'] === 'booked'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Réservé
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 border-b border-gray-200">
                                    <?php if ($availability['statut'] === 'available'): ?>
                                        <a href="planning.php?delete=<?= $availability['id'] ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette disponibilité ?')">
                                            <i class="fas fa-
