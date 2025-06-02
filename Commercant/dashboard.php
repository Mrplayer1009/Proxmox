<?php
session_start();
include '../php/db.php';

// Check if user is logged in and is a commerçant
if (!isset($_SESSION['user_id']) || $_SESSION['type_utilisateur'] !== 'commercant') {
    header('Location: ../php/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get commerçant information
$commercant_response = $api_client->get('users', ['id' => $user_id]);
$commercant = $commercant_response['data'] ?? [];

// Get commerçant's contract status
$contract_response = $api_client->get('commercants', ['id_utilisateur' => $user_id]);
$contract = $contract_response['data'] ?? [];

// Get recent announcements
$announcements_response = $api_client->get('annonces', [
    'id_utilisateur' => $user_id,
    'limit' => 3
]);
$recent_announcements = $announcements_response['data'] ?? [];

// Get recent invoices
$invoices_response = $api_client->get('factures', [
    'id_utilisateur' => $user_id,
    'limit' => 3
]);
$recent_invoices = $invoices_response['data'] ?? [];

// Get storage boxes
$boxes_response = $api_client->get('box_stockage', [
    'id_commercant' => $contract['id_commercant'] ?? 0
]);
$storage_boxes = $boxes_response['data'] ?? [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Commerçant - EcoDeli</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php include 'header_commercant.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Bienvenue, <?= htmlspecialchars($commercant['prenom'] . ' ' . $commercant['nom']) ?></h1>
            <p class="text-gray-600">Votre espace commerçant EcoDeli</p>
        </div>

        <!-- Informations sur le fonctionnement -->
        <div class="mb-8 bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <h2 class="text-xl font-semibold text-gray-800 mb-3">Partie dédiée aux commerçants</h2>
            <p class="mb-4 text-gray-600">
                Chaque commerçant souhaitant travailler avec EcoDeli doit pouvoir au travers d'un accès dédié suivre
                la gestion de ses activités et proposer des annonces.
            </p>
            <ul class="space-y-2 text-gray-600 pl-5">
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-yellow-500 mr-2 mt-0.5"></i>
                    <span>Gestion de votre contrat</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-yellow-500 mr-2 mt-0.5"></i>
                    <span>Gestion de vos annonces</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-yellow-500 mr-2 mt-0.5"></i>
                    <span>Gestion de la facturation des services demandés</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-yellow-500 mr-2 mt-0.5"></i>
                    <span>Accès aux paiements</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-yellow-500 mr-2 mt-0.5"></i>
                    <span>Gestion des annonces</span>
                </li>
            </ul>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-yellow-400">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Statut du contrat</h3>
                    <i class="fas fa-file-contract text-yellow-400 text-2xl"></i>
                </div>
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        <?= isset($contract['statut_validation']) && $contract['statut_validation'] === 'validé' ? 'bg-green-100 text-green-800' : 
                           (isset($contract['statut_validation']) && $contract['statut_validation'] === 'refusé' ? 'bg-red-100 text-red-800' : 
                           'bg-yellow-100 text-yellow-800') ?>">
                        <?= isset($contract['statut_validation']) ? ucfirst($contract['statut_validation']) : 'En attente' ?>
                    </span>
                    <p class="mt-2 text-sm text-gray-600">
                        <?php if (isset($contract['statut_validation']) && $contract['statut_validation'] === 'validé'): ?>
                            Votre contrat a été validé. Vous pouvez commencer à utiliser nos services.
                        <?php elseif (isset($contract['statut_validation']) && $contract['statut_validation'] === 'refusé'): ?>
                            Votre contrat a été refusé. Veuillez contacter notre service client pour plus d'informations.
                        <?php else: ?>
                            Votre contrat est en cours de validation. Nous vous contacterons dès que possible.
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-blue-500">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Annonces actives</h3>
                    <i class="fas fa-bullhorn text-blue-500 text-2xl"></i>
                </div>
                <div>
                    <span class="text-3xl font-bold text-gray-800">
                        <?= count(array_filter($recent_announcements, function($a) { return $a['statut'] === 'active'; })) ?>
                    </span>
                    <p class="mt-2 text-sm text-gray-600">Annonces en cours de diffusion</p>
                    <a href="annonces.php" class="mt-2 inline-block text-blue-600 hover:text-blue-800 text-sm">
                        Gérer mes annonces <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-green-500">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Espaces de stockage</h3>
                    <i class="fas fa-warehouse text-green-500 text-2xl"></i>
                </div>
                <div>
                    <span class="text-3xl font-bold text-gray-800">
                        <?= count($storage_boxes) ?>
                    </span>
                    <p class="mt-2 text-sm text-gray-600">Box de stockage actifs</p>
                    <a href="stockage.php" class="mt-2 inline-block text-green-600 hover:text-green-800 text-sm">
                        Gérer mes espaces <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Annonces récentes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Mes annonces récentes</h3>
                    <a href="annonces.php" class="text-sm text-blue-600 hover:underline">Voir tout</a>
                </div>
                
                <?php if (empty($recent_announcements)): ?>
                    <div class="text-center text-gray-500 py-4">
                        <i class="fas fa-bullhorn text-4xl mb-2"></i>
                        <p>Aucune annonce publiée</p>
                        <a href="ajouter_annonce.php" class="mt-2 inline-block text-blue-600 hover:text-blue-800">
                            Créer votre première annonce
                        </a>
                    </div>
                <?php else: ?>
                    <ul class="divide-y divide-gray-200">
                        <?php foreach ($recent_announcements as $announcement): ?>
                            <li class="py-3">
                                <div class="flex justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($announcement['description']) ?>
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            <?= htmlspecialchars($announcement['lieu_depart']) ?> → <?= htmlspecialchars($announcement['lieu_arrivee']) ?>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-900">
                                            <?= date('d/m/Y', strtotime($announcement['date_souhaitee'])) ?>
                                        </p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                            <?= $announcement['statut'] === 'active' ? 'bg-green-100 text-green-800' : 
                                               ($announcement['statut'] === 'en_cours' ? 'bg-blue-100 text-blue-800' : 
                                               ($announcement['statut'] === 'terminée' ? 'bg-gray-100 text-gray-800' : 
                                               'bg-red-100 text-red-800')) ?>">
                                            <?= ucfirst($announcement['statut']) ?>
                                        </span>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <div class="mt-4">
                        <a href="ajouter_annonce.php" class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i> Ajouter une annonce
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Factures récentes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Mes factures récentes</h3>
                    <a href="factures.php" class="text-sm text-blue-600 hover:underline">Voir tout</a>
                </div>
                
                <?php if (empty($recent_invoices)): ?>
                    <div class="text-center text-gray-500 py-4">
                        <i class="fas fa-file-invoice text-4xl mb-2"></i>
                        <p>Aucune facture disponible</p>
                    </div>
                <?php else: ?>
                    <ul class="divide-y divide-gray-200">
                        <?php foreach ($recent_invoices as $invoice): ?>
                            <li class="py-3">
                                <div class="flex justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            Facture #<?= $invoice['id_facture'] ?>
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            <?= ucfirst($invoice['type']) ?> - <?= $invoice['periode'] ?>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">
                                            <?= number_format($invoice['montant_total'], 2, ',', ' ') ?> €
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            <?= date('d/m/Y', strtotime($invoice['date_emission'])) ?>
                                        </p>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <div class="mt-4">
                        <a href="factures.php" class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                            <i class="fas fa-file-invoice mr-2"></i> Consulter mes factures
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include '../php/footer.php'; ?>
</body>
</html>
