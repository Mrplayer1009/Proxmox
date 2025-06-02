<?php
session_start();
require_once '../php/verif.php';
require_once '../php/api_client.php';

if (!isset($_SESSION['user_id']) || !in_array('livreur', $_SESSION['roles'])) {
    header('Location: ../index.php');
    exit;
}

$userId = $_SESSION['user_id'];

$livreurData = apiRequest("GET", "user/$userId", null);
$pendingDeliveries = apiRequest("GET", "intervention?user_id=$userId&status=pending", null);
$completedDeliveries = apiRequest("GET", "intervention?user_id=$userId&status=completed", null);
$announcements = apiRequest("GET", "annonce?user_id=$userId", null);

include 'header_livreur.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Tableau de bord Livreur</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Livraisons en attente -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Livraisons en attente</h2>
            <p class="text-4xl font-bold text-green-600 mb-2"><?= count($pendingDeliveries) ?></p>
            <a href="livraisons.php" class="text-blue-600 hover:underline">Voir toutes les livraisons</a>
        </div>
        
        <!-- Livraisons complétées -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Livraisons complétées</h2>
            <p class="text-4xl font-bold text-green-600 mb-2"><?= count($completedDeliveries) ?></p>
            <a href="livraisons.php?status=completed" class="text-blue-600 hover:underline">Voir l'historique</a>
        </div>
        
        <!-- Annonces actives -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Annonces actives</h2>
            <p class="text-4xl font-bold text-green-600 mb-2"><?= count($announcements) ?></p>
            <a href="annonces.php" class="text-blue-600 hover:underline">Gérer mes annonces</a>
        </div>
    </div>
    
    <!-- Livraisons récentes -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Livraisons récentes</h2>
        <?php if (count($pendingDeliveries) > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Adresse</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($pendingDeliveries, 0, 5) as $delivery): ?>
                            <tr>
                                <td class="py-2 px-4 border-b border-gray-200"><?= date('d/m/Y', strtotime($delivery['date'])) ?></td>
                                <td class="py-2 px-4 border-b border-gray-200"><?= $delivery['adresse'] ?></td>
                                <td class="py-2 px-4 border-b border-gray-200">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        En attente
                                    </span>
                                </td>
                                <td class="py-2 px-4 border-b border-gray-200">
                                    <a href="livraisons.php?id=<?= $delivery['id'] ?>" class="text-blue-600 hover:text-blue-900">Détails</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-gray-500">Aucune livraison en attente.</p>
        <?php endif; ?>
    </div>
    
    <!-- Accès rapides -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="annonces.php" class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg p-4 text-center">
            <span class="block text-lg font-semibold">Gérer mes annonces</span>
        </a>
        <a href="documents.php" class="bg-green-600 hover:bg-green-700 text-white rounded-lg p-4 text-center">
            <span class="block text-lg font-semibold">Mes documents</span>
        </a>
        <a href="planning.php" class="bg-purple-600 hover:bg-purple-700 text-white rounded-lg p-4 text-center">
            <span class="block text-lg font-semibold">Mon planning</span>
        </a>
        <a href="paiements.php" class="bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg p-4 text-center">
            <span class="block text-lg font-semibold">Mes paiements</span>
        </a>
    </div>
</div>

<?php include '../php/footer.php'; ?>
