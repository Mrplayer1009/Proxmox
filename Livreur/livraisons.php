<?php
session_start();
require_once '../php/verif.php';
require_once '../php/api_client.php';

if (!isset($_SESSION['user_id']) || !in_array('livreur', $_SESSION['roles'])) {
    header('Location: ../index.php');
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['intervention_id']) && isset($_POST['status'])) {
    $interventionId = $_POST['intervention_id'];
    $status = $_POST['status'];
    
    $data = [
        'status' => $status
    ];
    
    $result = apiRequest("PUT", "intervention/$interventionId", $data);
    
    if ($result && isset($result['success']) && $result['success']) {
        $successMessage = "Statut de la livraison mis à jour avec succès.";
    } else {
        $errorMessage = "Erreur lors de la mise à jour du statut.";
    }
}

$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';

$apiEndpoint = "intervention?user_id=$userId";
if ($statusFilter !== 'all') {
    $apiEndpoint .= "&status=$statusFilter";
}

$deliveries = apiRequest("GET", $apiEndpoint, null);

include 'header_livreur.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Mes Livraisons</h1>
    
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
    
    <!-- Filter tabs -->
    <div class="flex border-b border-gray-200 mb-6">
        <a href="livraisons.php?status=all" class="py-2 px-4 <?= $statusFilter === 'all' ? 'border-b-2 border-green-500 font-medium text-green-600' : 'text-gray-500 hover:text-gray-700' ?>">
            Toutes
        </a>
        <a href="livraisons.php?status=pending" class="py-2 px-4 <?= $statusFilter === 'pending' ? 'border-b-2 border-green-500 font-medium text-green-600' : 'text-gray-500 hover:text-gray-700' ?>">
            En attente
        </a>
        <a href="livraisons.php?status=in_progress" class="py-2 px-4 <?= $statusFilter === 'in_progress' ? 'border-b-2 border-green-500 font-medium text-green-600' : 'text-gray-500 hover:text-gray-700' ?>">
            En cours
        </a>
        <a href="livraisons.php?status=completed" class="py-2 px-4 <?= $statusFilter === 'completed' ? 'border-b-2 border-green-500 font-medium text-green-600' : 'text-gray-500 hover:text-gray-700' ?>">
            Terminées
        </a>
        <a href="livraisons.php?status=cancelled" class="py-2 px-4 <?= $statusFilter === 'cancelled' ? 'border-b-2 border-green-500 font-medium text-green-600' : 'text-gray-500 hover:text-gray-700' ?>">
            Annulées
        </a>
    </div>
    
    <?php if (empty($deliveries)): ?>
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <p class="text-gray-500">Aucune livraison trouvée pour ce filtre.</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Adresse</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($deliveries as $delivery): ?>
                            <tr>
                                <td class="py-3 px-4 border-b border-gray-200"><?= $delivery['id'] ?></td>
                                <td class="py-3 px-4 border-b border-gray-200"><?= date('d/m/Y', strtotime($delivery['date'])) ?></td>
                                <td class="py-3 px-4 border-b border-gray-200"><?= htmlspecialchars($delivery['client_nom']) ?></td>
                                <td class="py-3 px-4 border-b border-gray-200"><?= htmlspecialchars($delivery['adresse']) ?></td>
                                <td class="py-3 px-4 border-b border-gray-200"><?= htmlspecialchars($delivery['type']) ?></td>
                                <td class="py-3 px-4 border-b border-gray-200">
                                    <?php if ($delivery['status'] === 'pending'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            En attente
                                        </span>
                                    <?php elseif ($delivery['status'] === 'in_progress'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            En cours
                                        </span>
                                    <?php elseif ($delivery['status'] === 'completed'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Terminée
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Annulée
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 border-b border-gray-200">
                                    <a href="details_livraison.php?id=<?= $delivery['id'] ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i> Détails
                                    </a>
                                    
                                    <?php if ($delivery['status'] === 'pending'): ?>
                                        <form method="POST" action="" class="inline">
                                            <input type="hidden" name="intervention_id" value="<?= $delivery['id'] ?>">
                                            <input type="hidden" name="status" value="in_progress">
                                            <button type="submit" class="text-blue-600 hover:text-blue-900 mr-3">
                                                <i class="fas fa-play"></i> Démarrer
                                            </button>
                                        </form>
                                    <?php elseif ($delivery['status'] === 'in_progress'): ?>
                                        <form method="POST" action="" class="inline">
                                            <input type="hidden" name="intervention_id" value="<?= $delivery['id'] ?>">
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                                <i class="fas fa-check"></i> Terminer
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <?php if ($delivery['status'] !== 'completed' && $delivery['status'] !== 'cancelled'): ?>
                                        <form method="POST" action="" class="inline">
                                            <input type="hidden" name="intervention_id" value="<?= $delivery['id'] ?>">
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette livraison ?')">
                                                <i class="fas fa-times"></i> Annuler
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../php/footer.php'; ?>
