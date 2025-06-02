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

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : null;
$status = isset($_GET['status']) ? $_GET['status'] : 'all';

$api_query = "factures?prestataire_id=$user_id&year=$year";
if ($month) {
    $api_query .= "&month=$month";
}
if ($status !== 'all') {
    $api_query .= "&status=$status";
}

$response = $api_client->get($api_query);
$invoices = json_decode($response, true) ?? [];

usort($invoices, function($a, $b) {
    return strtotime($b['date_creation']) - strtotime($a['date_creation']);
});

$years_response = $api_client->get("factures/years?prestataire_id=$user_id");
$available_years = json_decode($years_response, true) ?? [date('Y')];

$total_amount = 0;
$total_paid = 0;
$total_pending = 0;

foreach ($invoices as $invoice) {
    $total_amount += $invoice['montant_total'];
    if ($invoice['statut_paiement'] === 'Payé') {
        $total_paid += $invoice['montant_total'];
    } else {
        $total_pending += $invoice['montant_total'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Factures - EcoDeli</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include 'header_prestataire.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Mes Factures</h1>
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Montant total</h2>
                <p class="text-3xl font-bold text-gray-900"><?= number_format($total_amount, 2, ',', ' ') ?> €</p>
                <p class="text-sm text-gray-500 mt-1">Pour la période sélectionnée</p>
            </div>
            
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Montant payé</h2>
                <p class="text-3xl font-bold text-green-600"><?= number_format($total_paid, 2, ',', ' ') ?> €</p>
                <p class="text-sm text-gray-500 mt-1">Factures réglées</p>
            </div>
            
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Montant en attente</h2>
                <p class="text-3xl font-bold text-yellow-600"><?= number_format($total_pending, 2, ',', ' ') ?> €</p>
                <p class="text-sm text-gray-500 mt-1">Factures en attente de paiement</p>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Filtres</h2>
            <form method="get" class="flex flex-wrap gap-4">
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Année</label>
                    <select id="year" name="year" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <?php foreach ($available_years as $y): ?>
                            <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Mois</label>
                    <select id="month" name="month" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tous les mois</option>
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>" <?= $month == $m ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select id="status" name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>Tous</option>
                        <option value="paid" <?= $status === 'paid' ? 'selected' : '' ?>>Payé</option>
                        <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>En attente</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Invoices List -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Liste des factures</h2>
            
            <?php if (empty($invoices)): ?>
                <p class="text-gray-500 text-center py-8">Aucune facture trouvée avec les filtres sélectionnés</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Facture</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($invoices as $invoice): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= $invoice['numero_facture'] ?? 'F-' . $invoice['id'] ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('d/m/Y', strtotime($invoice['date_creation'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('F Y', strtotime($invoice['periode_debut'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= number_format($invoice['montant_total'], 2, ',', ' ') ?> €
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($invoice['statut_paiement'] === 'Payé'): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Payé
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                En attente
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="voir_facture.php?id=<?= $invoice['id'] ?>" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                                        <a href="telecharger_facture.php?id=<?= $invoice['id'] ?>" class="text-green-600 hover:text-green-900">Télécharger</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
