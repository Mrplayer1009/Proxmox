<?php
session_start();
require_once '../php/verif.php';
require_once '../php/api_client.php';

// Verify if user is logged in and has the commerçant role
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'commercant') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$api_client = new ApiClient();

// Get all invoices for this merchant
$response = $api_client->get("achat?user_id=$user_id");
$invoices = json_decode($response, true);

// Filter by month if requested
$selected_month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de la facturation - EcoDeli</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include 'header_commercant.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Gestion de la facturation</h1>
        
        <div class="mb-6 bg-white shadow-md rounded-lg p-4">
            <h2 class="text-xl font-semibold mb-4">Filtrer par mois</h2>
            <form method="get" class="flex items-center">
                <input type="month" name="month" value="<?= $selected_month ?>" class="shadow border rounded py-2 px-3 text-gray-700 mr-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Filtrer
                </button>
            </form>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <?php if (empty($invoices)): ?>
                <div class="p-6 text-center text-gray-500">
                    Aucune facture trouvée pour cette période.
                </div>
            <?php else: ?>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Facture</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($invoices as $invoice): ?>
                            <?php 
                            // Filter by selected month
                            $invoice_date = substr($invoice['date_achat'], 0, 7);
                            if ($invoice_date !== $selected_month) continue;
                            ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($invoice['id']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($invoice['date_achat']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($invoice['service_name'] ?? 'Service') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($invoice['montant']) ?> €</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($invoice['statut'] === 'payé'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Payé
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            En attente
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="voir_facture.php?id=<?= $invoice['id'] ?>" class="text-indigo-600 hover:text-indigo-900">Voir détails</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
