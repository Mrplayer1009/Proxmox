<?php
session_start();
require_once '../php/verif.php';
require_once '../php/api_client.php';

if (!isset($_SESSION['user_id']) || !in_array('livreur', $_SESSION['roles'])) {
    header('Location: ../index.php');
    exit;
}

$userId = $_SESSION['user_id'];

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $annonceId = $_GET['delete'];
    $result = apiRequest("DELETE", "annonce/$annonceId", null);
    
    if ($result && isset($result['success']) && $result['success']) {
        $successMessage = "Annonce supprimée avec succès.";
    } else {
        $errorMessage = "Erreur lors de la suppression de l'annonce.";
    }
}

// Get announcements from API
$announcements = apiRequest("GET", "annonce?user_id=$userId", null);

include 'header_livreur.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Mes Annonces</h1>
        <a href="ajouter_annonce.php" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-plus mr-2"></i>Ajouter une annonce
        </a>
    </div>
    
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
    
    <?php if (empty($announcements)): ?>
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <p class="text-gray-500 mb-4">Vous n'avez pas encore d'annonces.</p>
            <a href="ajouter_annonce.php" class="text-blue-600 hover:underline">Créer votre première annonce</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($announcements as $annonce): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold mb-2"><?= htmlspecialchars($annonce['titre']) ?></h2>
                        <p class="text-gray-600 mb-4"><?= htmlspecialchars($annonce['description']) ?></p>
                        
                        <div class="flex items-center text-sm text-gray-500 mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>De: <?= htmlspecialchars($annonce['lieu_depart']) ?></span>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-500 mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>À: <?= htmlspecialchars($annonce['lieu_arrivee']) ?></span>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-500 mb-2">
                            <i class="fas fa-calendar mr-2"></i>
                            <span>Date: <?= date('d/m/Y', strtotime($annonce['date'])) ?></span>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            <i class="fas fa-truck mr-2"></i>
                            <span>Type: <?= htmlspecialchars($annonce['type']) ?></span>
                        </div>
                        
                        <div class="flex justify-between mt-4">
                            <a href="modifier_annonce.php?id=<?= $annonce['id'] ?>" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit mr-1"></i> Modifier
                            </a>
                            <a href="annonces.php?delete=<?= $annonce['id'] ?>" class="text-red-600 hover:text-red-800" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')">
                                <i class="fas fa-trash mr-1"></i> Supprimer
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../php/footer.php'; ?>
