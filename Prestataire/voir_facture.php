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
    header('Location: factures.php');
    exit;
}

$invoice_id = $_GET['id'];

$response = $api_client->get("factures/$invoice_id");
$invoice = json_decode($response, true);

if (!$invoice || $invoice['prestataire_id'] != $user_id) {
    header('Location: factures.php');
    exit;
}

$response = $api_client->get("factures/$invoice_id/items");
$invoice_items = json_decode($response, true) ?? [];

// Get prestataire details
$response = $api_client->get("users/$user_id");
$prestataire = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture <?= $invoice['numero_facture'] ?? 'F-' . $invoice['id'] ?> - EcoDeli</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include 'header_prestataire.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold">Facture <?= $invoice['numero_facture'] ?? 'F-' . $invoice['id'] ?></h1>
            <div class="flex space-x-3">
                <a href="factures.php" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300">
                    &larr; Retour aux factures
                </a>
                <a href="telecharger_facture.php?id=<?= $invoice['id'] ?>" class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700">
                    Télécharger PDF
                </a>
            </div>
        </div>
        
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <!-- Invoice Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex flex-wrap items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold">Facture <?= $invoice['numero_facture'] ?? 'F-' . $invoice['id'] ?></h2>
                        <p class="text-gray-500">Date: <?= date('d/m/Y', strtotime($invoice['date_creation'])) ?></p>
                    </div>
                    <div>
                        <?php if ($invoice['statut_paiement'] === 'Payé'): ?>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Payé
                            </span>
                        <?php else: ?>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                En attente
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Invoice Content -->
            <div class="p-6">
                <!-- Addresses -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- From -->
                    <div>
                        <h3 class="text-lg font-semibold mb-2">De</h3>
                        <div class="border border-gray-200 rounded-md p-4">
                            <p class="font-medium">EcoDeli</p>
                            <p>123 Rue de l'Écologie</p>
                            <p>75001 Paris</p>
                            <p>France</p>
                            <p>Email: contact@ecodeli.fr</p>
                            <p>Téléphone: 01 23 45 67 89</p>
                        </div>
                    </div>
                    
                    <!-- To -->
                    <div>
                        <h3 class="text-lg font-semibold mb-2">À</h3>
                        <div class="border border-gray-200 rounded-md p-4">
                            <p class="font-medium"><?= htmlspecialchars($prestataire['prenom'] . ' ' . $prestataire['nom']) ?></p>
                            <p><?= htmlspecialchars($prestataire['adresse'] ?? 'Adresse non spécifiée') ?></p>
                            <p>Email: <?= htmlspecialchars($prestataire['email']) ?></p>
                            <p>Téléphone: <?= htmlspecialchars($prestataire['telephone'] ?? 'Non spécifié') ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Invoice Details -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Détails de la facture</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (empty($invoice_items)): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Aucun détail disponible</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($invoice_items as $item): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= htmlspecialchars($item['description']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= date('d/m/Y', strtotime($item['date'])) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= $item['quantite'] ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= number_format($item['prix_unitaire'], 2, ',', ' ') ?> €
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= number_format($item['prix_total'], 2, ',', ' ') ?> €
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Invoice Summary -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex justify-end">
                        <div class="w-full md:w-1/3">
                            <div class="flex justify-between py-2">
                                <span class="font-medium">Sous-total:</span>
                                <span><?= number_format($invoice['montant_ht'], 2, ',', ' ') ?> €</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="font-medium">TVA (<?= $invoice['taux_tva'] ?>%):</span>
                                <span><?= number_format($invoice['montant_tva'], 2, ',', ' ') ?> €</span>
                            </div>
                            <div class="flex justify-between py-2 text-lg font-bold">
                                <span>Total:</span>
                                <span><?= number_format($invoice['montant_total'], 2, ',', ' ') ?> €</span>
                            </div>
                            <?php if ($invoice['statut_paiement'] === 'Payé'): ?>
                                <div class="mt-4 bg-green-100 text-green-800 p-3 rounded-md">
                                    <p class="font-medium">Payé le: <?= date('d/m/Y', strtotime($invoice['date_paiement'])) ?></p>
                                    <p>Méthode: <?= htmlspecialchars($invoice['methode_paiement'] ?? 'Virement bancaire') ?></p>
                                </div>
                            <?php else: ?>
                                <div class="mt-4 bg-yellow-100 text-yellow-800 p-3 rounded-md">
                                    <p class="font-medium">En attente de paiement</p>
                                    <p>Date d'échéance: <?= date('d/m/Y', strtotime($invoice['date_echeance'])) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Notes -->
                <?php if (!empty($invoice['notes'])): ?>
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold mb-2">Notes</h3>
                        <p class="text-gray-700"><?= nl2br(htmlspecialchars($invoice['notes'])) ?></p>
                    </div>
                <?php endif; ?>
                
                <!-- Terms -->
                <div class="mt-8 border-t border-gray-200 pt-6 text-sm text-gray-600">
                    <h3 class="text-lg font-semibold mb-2">Conditions de paiement</h3>
                    <p>Paiement à effectuer dans les 30 jours suivant la date de facturation.</p>
                    <p>Merci de votre collaboration avec EcoDeli.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
