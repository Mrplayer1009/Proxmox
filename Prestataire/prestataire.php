<?php
session_start();
include '../php/verif.php';
include '../php/api_client.php';

if (!isset($_SESSION['user_id']) || !in_array('prestataire', $_SESSION['roles'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$prestataire_response = apiRequest("GET", "users/$user_id", null);
$prestataire = $prestataire_response ?? [];

$rating_response = apiRequest("GET", "evaluations?prestataire_id=$user_id&action=rating", null);
$rating = $rating_response ?? ['note_moyenne' => 0, 'nombre_evaluations' => 0];

$interventions_response = apiRequest("GET", "interventions?prestataire_id=$user_id&status=upcoming&limit=5", null);
$interventions = $interventions_response ?? [];

$factures_response = apiRequest("GET", "factures?prestataire_id=$user_id&limit=3", null);
$factures = $factures_response ?? [];

include 'header_prestataire.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Bienvenue, <?= htmlspecialchars($prestataire['prenom'] . ' ' . $prestataire['nom']) ?></h1>
        <p class="text-gray-600">Votre espace prestataire EcoDeli</p>
    </div>

    <!-- Informations sur le fonctionnement -->
    <div class="mb-8 bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <h2 class="text-xl font-semibold text-gray-800 mb-3">Partie dédiée aux prestataires</h2>
        <p class="mb-4 text-gray-600">
            Le principe de fonctionnement de EcoDeli est d'offrir à ses clients des prestations de qualité et régulières. 
            Pour y parvenir, elle dispose d'une base de prestataires qu'elle a rigoureusement sélectionnée et validée.
        </p>
        <ul class="space-y-2 text-gray-600 pl-5">
            <li class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Suivi des évaluations des prestations réalisées (note donnée par les clients ayant utilisé vos services)</span>
            </li>
            <li class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Validation de la sélection des prestataires, des types de prestations et des tarifs pratiqués</span>
            </li>
            <li class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Calendrier des disponibilités pour planifier vos interventions</span>
            </li>
            <li class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Gestion complète de vos interventions</span>
            </li>
            <li class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Facturation automatique mensuelle et suivi des paiements</span>
            </li>
        </ul>
    </div>

    <!-- informations -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-yellow-400">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Note moyenne</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            </div>
            <div class="flex items-end">
                <span class="text-3xl font-bold text-gray-800">
                    <?= number_format($rating['note_moyenne'] ?? 0, 1) ?>
                </span>
                <span class="text-lg text-gray-600 ml-1">/5</span>
                <span class="text-sm text-gray-500 ml-auto">
                    <?= $rating['nombre_evaluations'] ?? 0 ?> évaluations
                </span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-green-500">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Statut</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Validé
                </span>
                <p class="mt-2 text-sm text-gray-600">Vous êtes un prestataire actif chez EcoDeli</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-blue-500">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Vos services</h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                    <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                </svg>
            </div>
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                    Entretien
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Réparation
                </span>
                <p class="mt-2 text-sm text-gray-600">Gérez vos services depuis votre profil</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Interventions à venir -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Interventions à venir</h3>
                <a href="interventions.php" class="text-sm text-blue-600 hover:underline">Voir tout</a>
            </div>
            
            <?php if (empty($interventions)): ?>
                <p class="text-gray-500 text-center py-4">Aucune intervention planifiée</p>
            <?php else: ?>
                <ul class="divide-y divide-gray-200">
                    <?php foreach ($interventions as $intervention): ?>
                        <li class="py-3">
                            <div class="flex justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($intervention['type_service'] ?? 'Service') ?></p>
                                    <p class="text-sm text-gray-500">
                                        Client: <?= htmlspecialchars(($intervention['client_prenom'] ?? '') . ' ' . ($intervention['client_nom'] ?? '')) ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-900">
                                        <?= isset($intervention['date_intervention']) ? date('d/m/Y', strtotime($intervention['date_intervention'])) : '' ?>
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        <?= isset($intervention['heure_intervention']) ? date('H:i', strtotime($intervention['heure_intervention'])) : (isset($intervention['heure_debut']) ? date('H:i', strtotime($intervention['heure_debut'])) : '') ?>
                                    </p>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <!-- Dernières factures -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Dernières factures</h3>
                <a href="factures.php" class="text-sm text-blue-600 hover:underline">Voir tout</a>
            </div>
            
            <?php if (empty($factures)): ?>
                <p class="text-gray-500 text-center py-4">Aucune facture disponible</p>
            <?php else: ?>
                <ul class="divide-y divide-gray-200">
                    <?php foreach ($factures as $facture): ?>
                        <li class="py-3">
                            <div class="flex justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Facture #<?= $facture['id'] ?? '' ?></p>
                                    <p class="text-sm text-gray-500">
                                        <?= isset($facture['date_creation']) ? date('d/m/Y', strtotime($facture['date_creation'])) : '' ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">
                                        <?= isset($facture['montant_total']) ? number_format($facture['montant_total'], 2, ',', ' ') . ' €' : '' ?>
                                    </p>
                                    <p class="text-sm">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                            <?= isset($facture['statut_paiement']) && $facture['statut_paiement'] == 'Payé' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                            <?= $facture['statut_paiement'] ?? 'En attente' ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            
            <div class="mt-4">
                <a href="factures.php" class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Consulter mes factures
                </a>
            </div>
        </div>
    </div>

    <!-- Accès rapides -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="agenda.php" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500 mr-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-700">Agenda</h3>
            </div>
            <p class="text-gray-600">Gérez vos disponibilités et consultez votre planning</p>
        </a>
        
        <a href="interventions.php" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500 mr-3" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-700">Interventions</h3>
            </div>
            <p class="text-gray-600">Consultez et gérez vos interventions</p>
        </a>
        
        <a href="evaluations.php" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-400 mr-3" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-700">Évaluations</h3>
            </div>
            <p class="text-gray-600">Consultez les avis de vos clients</p>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Vous pouvez ajouter ici des scripts pour des graphiques ou des fonctionnalités interactives
</script>
