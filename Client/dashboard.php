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

// Get user information
$response = $api_client->get("user/$user_id");
$user = json_decode($response, true);

// Get recent announcements
$response = $api_client->get("annonce?limit=5");
$announcements = json_decode($response, true);

// Get upcoming appointments
$response = $api_client->get("intervention?user_id=$user_id&status=pending&limit=5");
$appointments = json_decode($response, true);

// Check if it's the first login
$first_login = isset($_SESSION['first_login']) && $_SESSION['first_login'] === true;
if ($first_login) {
    // Reset the first login flag
    $_SESSION['first_login'] = false;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - EcoDeli</title>
    <link href="../src/output.css" rel="stylesheet">
    <?php if ($first_login): ?>
    <style>
        .tutorial-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .tutorial-content {
            background-color: white;
            border-radius: 8px;
            padding: 2rem;
            max-width: 600px;
            color: black;
        }
        .tutorial-step {
            display: none;
        }
        .tutorial-step.active {
            display: block;
        }
    </style>
    <?php endif; ?>
</head>
<body class="bg-gray-100">
    <?php include 'header_client.php'; ?>

    <?php if ($first_login): ?>
    <!-- Tutorial Overlay for First Login -->
    <div class="tutorial-overlay" id="tutorial">
        <div class="tutorial-content">
            <div class="tutorial-step active" data-step="1">
                <h2 class="text-2xl font-bold mb-4">Bienvenue sur EcoDeli!</h2>
                <p class="mb-4">Nous sommes ravis de vous accueillir sur notre plateforme. Ce tutoriel vous guidera à travers les fonctionnalités principales.</p>
                <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded next-step">Suivant</button>
            </div>
            <div class="tutorial-step" data-step="2">
                <h2 class="text-2xl font-bold mb-4">Déposer une annonce</h2>
                <p class="mb-4">Vous pouvez facilement déposer une annonce pour demander un service de livraison ou autre prestation.</p>
                <button class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2 prev-step">Précédent</button>
                <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded next-step">Suivant</button>
            </div>
            <div class="tutorial-step" data-step="3">
                <h2 class="text-2xl font-bold mb-4">Réserver un service</h2>
                <p class="mb-4">Parcourez les services disponibles et réservez directement en ligne.</p>
                <button class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2 prev-step">Précédent</button>
                <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded next-step">Suivant</button>
            </div>
            <div class="tutorial-step" data-step="4">
                <h2 class="text-2xl font-bold mb-4">Gérer vos paiements</h2>
                <p class="mb-4">Suivez et gérez facilement tous vos paiements depuis votre tableau de bord.</p>
                <button class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2 prev-step">Précédent</button>
                <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded close-tutorial">Terminer</button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Bienvenue, <?= htmlspecialchars($user['prenom'] ?? 'Client') ?>!</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Recent Announcements -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Annonces récentes</h2>
                <?php if (empty($announcements)): ?>
                    <p class="text-gray-500">Aucune annonce récente.</p>
                <?php else: ?>
                    <ul class="divide-y divide-gray-200">
                        <?php foreach ($announcements as $announcement): ?>
                            <li class="py-3">
                                <h3 class="font-medium"><?= htmlspecialchars($announcement['titre']) ?></h3>
                                <p class="text-sm text-gray-600"><?= htmlspecialchars(substr($announcement['description'], 0, 100)) ?>...</p>
                                <a href="voir_annonce.php?id=<?= $announcement['id'] ?>" class="text-blue-500 hover:underline text-sm">Voir plus</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="mt-4">
                        <a href="annonces.php" class="text-blue-500 hover:underline">Voir toutes les annonces</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Upcoming Appointments -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Rendez-vous à venir</h2>
                <?php if (empty($appointments)): ?>
                    <p class="text-gray-500">Aucun rendez-vous à venir.</p>
                <?php else: ?>
                    <ul class="divide-y divide-gray-200">
                        <?php foreach ($appointments as $appointment): ?>
                            <li class="py-3">
                                <h3 class="font-medium"><?= htmlspecialchars($appointment['titre'] ?? 'Rendez-vous') ?></h3>
                                <p class="text-sm text-gray-600">Date: <?= htmlspecialchars($appointment['date_intervention']) ?></p>
                                <a href="voir_rendez_vous.php?id=<?= $appointment['id'] ?>" class="text-blue-500 hover:underline text-sm">Détails</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="mt-4">
                        <a href="rendez_vous.php" class="text-blue-500 hover:underline">Voir tous les rendez-vous</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <!-- Quick Actions -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Actions rapides</h2>
                <div class="space-y-2">
                    <a href="deposer_annonce.php" class="block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded text-center">
                        Déposer une annonce
                    </a>
                    <a href="services.php" class="block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded text-center">
                        Réserver un service
                    </a>
                    <a href="box_stockage.php" class="block bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded text-center">
                        Accéder aux box de stockage
                    </a>
                </div>
            </div>
            
            <!-- Recent Payments -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Paiements récents</h2>
                <a href="paiements.php" class="text-blue-500 hover:underline">Gérer mes paiements</a>
            </div>
            
            <!-- Storage Box Status -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Statut des box</h2>
                <a href="box_stockage.php" class="text-blue-500 hover:underline">Voir mes box de stockage</a>
            </div>
        </div>
    </div>

    <?php if ($first_login): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 1;
            const totalSteps = document.querySelectorAll('.tutorial-step').length;
            
            // Next button functionality
            document.querySelectorAll('.next-step').forEach(button => {
                button.addEventListener('click', function() {
                    document.querySelector(`.tutorial-step[data-step="${currentStep}"]`).classList.remove('active');
                    currentStep++;
                    document.querySelector(`.tutorial-step[data-step="${currentStep}"]`).classList.add('active');
                });
            });
            
            // Previous button functionality
            document.querySelectorAll('.prev-step').forEach(button => {
                button.addEventListener('click', function() {
                    document.querySelector(`.tutorial-step[data-step="${currentStep}"]`).classList.remove('active');
                    currentStep--;
                    document.querySelector(`.tutorial-step[data-step="${currentStep}"]`).classList.add('active');
                });
            });
            
            // Close tutorial
            document.querySelector('.close-tutorial').addEventListener('click', function() {
                document.getElementById('tutorial').style.display = 'none';
            });
        });
    </script>
    <?php endif; ?>
</body>
</html>
