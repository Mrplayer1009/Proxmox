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

// Get all payments for this merchant
$response = $api_client->get("achat?user_id=$user_id");
$payments = json_decode($response, true);

// Process payment if requested
$success_message = '';
$error_message = '';

if (isset($_POST['pay_invoice'])) {
    $invoice_id = $_POST['invoice_id'];
    
    // Simulate payment process
    $payment_data = [
        'id' => $invoice_id,
        'statut' => 'payé',
        'date_paiement' => date('Y-m-d H:i:s')
    ];
    
    $response = $api_client->put("achat/$invoice_id", $payment_data);
    $result = json_decode($response, true);
    
    if (isset($result['success']) && $result['success']) {
        $success_message = 'Paiement effectué avec succès!';
        // Refresh payments list
        $response = $api_client->get("achat?user_id=$user_id");
        $payments = json_decode($response, true);
    } else {
        $error_message = 'Une erreur est survenue lors du paiement.';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des paiements - EcoDeli</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include 'header_commercant.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Gestion des paiements</h1>
        
        <?php if ($success_message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= $success_message ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= $error_message ?>
            </div>
        <?php endif; ?>
        
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <h2 class="text-xl font-semibold p-4 bg-gray-50 border-b">Factures en attente de paiement</h2>
            
            <?php 
            $pending_payments = array_filter($payments, function($payment) {
                return $payment['statut'] !== 'payé';
            });
            ?>
            
            <?php if (empty($pending_payments)): ?>
                <div class="p-6 text-center text-gray-500">
                    Aucune facture en attente de paiement.
                </div>
            <?php else: ?>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Facture</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($pending_payments as $payment): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($payment['id']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($payment['date_achat']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($payment['service_name'] ?? 'Service') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($payment['montant']) ?> €</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form method="post" class="inline">
                                        <input type="hidden" name="invoice_id" value="<?= $payment['id'] ?>">
                                        <button type="submit" name="pay_invoice" class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded text-sm">
                                            Payer maintenant
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <div class="mt-8 bg-white shadow-md rounded-lg overflow-hidden">
            <h2 class="text-xl font-semibold p-4 bg-gray-50 border-b">Historique des paiements</h2>
            
            <?php 
            $paid_payments = array_filter($payments, function($payment) {
                return $payment['statut'] === 'payé';
            });
            ?>
            
            <?php if (empty($paid_payments)): ?>
                <div class="p-6 text-center text-gray-500">
                    Aucun historique de paiement.
                </div>
            <?php else: ?>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Facture</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de paiement</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($paid_payments as $payment): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($payment['id']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($payment['date_achat']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($payment['service_name'] ?? 'Service') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($payment['montant']) ?> €</td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($payment['date_paiement'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
