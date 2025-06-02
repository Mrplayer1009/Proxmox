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

$response = $api_client->get("user/$user_id");
$user = json_decode($response, true);

$response = $api_client->get("intervention?prestataire_id=$user_id&status=pending");
$upcoming_interventions = json_decode($response, true);

$response = $api_client->get("evaluation?prestataire_id=$user_id&limit=5");
$recent_evaluations = json_decode($response, true);

$total_rating = 0;
$rating_count = count($recent_evaluations);
if ($rating_count > 0) {
    foreach ($recent_evaluations as $evaluation) {
        $total_rating += $evaluation['note'];
    }
    $average_rating = $total_rating / $rating_count;
} else {
    $average_rating = 0;
}

$current_month = date('Y-m');
$response = $api_client->get("achat?prestataire_id=$user_id");
$all_payments = json_decode($response, true);
$monthly_earnings = 0;

foreach ($all_payments as $payment) {
    $payment_date = substr($payment['date_achat'], 0, 7);
    if ($payment_date === $current_month && $payment['statut'] === 'payé') {
        $monthly_earnings += $payment['montant'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Prestataire - EcoDeli</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include 'header_prestataire.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Bienvenue, <?= htmlspecialchars($user['prenom'] ?? 'Prestataire') ?>!</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Note moyenne</h2>
                <div class="flex items-center">
                    <span class="text-3xl font-bold text-yellow-500"><?= number_format($average_rating, 1) ?></span>
                    <span class="text-xl text-gray-500 ml-2">/5</span>
                    <div class="ml-2 flex">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= round($average_rating)): ?>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            <?php else: ?>
                                <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-1">Basé sur <?= $rating_count ?> évaluations</p>
            </div>
            
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Interventions à venir</h2>
                <div class="flex items-center">
                    <span class="text-3xl font-bold text-blue-500"><?= count($upcoming_interventions) ?></span>
                </div>
                <p class="text-sm text-gray-500 mt-1">Interventions planifiées</p>
                <a href="interventions.php" class="text-blue-500 hover:underline text-sm mt-2 inline-block">Voir toutes</a>
            </div>
            
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Gains du mois</h2>
                <div class="flex items-center">
                    <span class="text-3xl font-bold text-green-500"><?= number_format($monthly_earnings, 2) ?> €</span>
                </div>
                <p class="text-sm text-gray-500 mt-1">Pour <?= date('F Y') ?></p>
                <a href="factures.php" class="text-blue-500 hover:underline text-sm mt-2 inline-block">Voir les factures</a>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Interventions à venir</h2>
                <?php if (empty($upcoming_interventions)): ?>
                    <p class="text-gray-500">Aucune intervention planifiée.</p>
                <?php else: ?>
                    <ul class="divide-y divide-gray-200">
                        <?php foreach (array_slice($upcoming_interventions, 0, 5) as $intervention): ?>
                            <li class="py-3">
                                <div class="flex justify-between">
                                    <div>
                                        <h3 class="font-medium"><?= htmlspecialchars($intervention['titre'] ?? 'Intervention') ?></h3>
                                        <p class="text-sm text-gray-600">Date: <?= htmlspecialchars($intervention['date_intervention']) ?></p>
                                    </div>
                                    <a href="voir_intervention.php?id=<?= $intervention['id'] ?>" class="text-blue-500 hover:underline text-sm">Détails</a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if (count($upcoming_interventions) > 5): ?>
                        <div class="mt-4">
                            <a href="interventions.php" class="text-blue-500 hover:underline">Voir toutes les interventions</a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Évaluations récentes</h2>
                <?php if (empty($recent_evaluations)): ?>
                    <p class="text-gray-500">Aucune évaluation récente.</p>
                <?php else: ?>
                    <ul class="divide-y divide-gray-200">
                        <?php foreach ($recent_evaluations as $evaluation): ?>
                            <li class="py-3">
                                <div class="flex items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?php if ($i <= $evaluation['note']): ?>
                                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                <?php else: ?>
                                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            <span class="text-sm text-gray-500 ml-2"><?= htmlspecialchars($evaluation['date_evaluation']) ?></span>
                                        </div>
                                        <p class="text-sm mt-1"><?= htmlspecialchars($evaluation['commentaire']) ?></p>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="mt-4">
                        <a href="evaluations.php" class="text-blue-500 hover:underline">Voir toutes les évaluations</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-6 grid grid-cols-1 gap-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Actions rapides</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="disponibilites.php" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded text-center">
                        Mettre à jour mes disponibilités
                    </a>
                    <a href="interventions.php" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded text-center">
                        Gérer mes interventions
                    </a>
                    <a href="factures.php" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-4 rounded text-center">
                        Consulter mes factures
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
