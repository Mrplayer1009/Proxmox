<?php
session_start();
require_once '../php/verif.php';
require_once '../php/api_client.php';

if (!isset($_SESSION['user_id']) || !in_array('livreur', $_SESSION['roles'])) {
    header('Location: ../index.php');
    exit;
}

$userId = $_SESSION['user_id'];

$payments = apiRequest("GET", "paiement?user_id=$userId", null);

$totalEarnings = 0;
$pendingEarnings = 0;
$paidEarnings = 0;

if (!empty($payments)) {
    foreach ($payments as $payment) {
        $totalEarnings += $payment['montant'];
        
        if ($payment['statut'] === 'paid') {
            $paidEarnings += $payment['montant'];
        } else {
            $pendingEarnings += $payment['montant'];
        }
    }
}

include 'header_livreur.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Mes Paiements</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-600 mb-2">Gains totaux</h2>
            <p class="text-3xl font-bold text-green-600"><?= number_format($totalEarnings, 2, ',', ' ') ?> €</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-600 mb-2">Gains payés</h2>
            <p class="text-3xl font-bold text-blue-600"><?= number_format($paidEarnings, 2, ',', ' ') ?> €</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-600 mb-2">Gains en attente</h2>
            <p class="text-3xl font-bold text-yellow-600"><?= number_format($pendingEarnings, 2, ',', ' ') ?> €</p>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold">Historique des paiements</h2>
        </div>
        
        <?php if (empty($payments)): ?>
            <div class="p-6 text-center">
                <p class="text-gray-500">Aucun paiement trouvé.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Montant</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td class="py-3 px-4 border-b border-gray-200"><?= $payment['id'] ?></td>
                                <td class="py-3 px-4 border-b border-gray-200"><?= date('d/m/Y', strtotime($payment['date'])) ?></td>
                                <td class="py-3 px-4 border-b border-gray-200"><?= htmlspecialchars($payment['description']) ?></td>
                                <td class="py-3 px-4 border-b border-gray-200 font-semibold"><?= number_format($payment['montant'], 2, ',', ' ') ?> €</td>
                                <td class="py-3 px-4 border-b border-gray-200">
                                    <?php if ($payment['statut'] === 'paid'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Payé
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            En attente
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Informations bancaires</h2>
        
        <form method="POST" action="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="titulaire" class="block text-gray-700 text-sm font-bold mb-2">Titulaire du compte</label>
                    <input type="text" id="titulaire" name="titulaire" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                
                <div>
                    <label for="iban" class="block text-gray-700 text-sm font-bold mb-2">IBAN</label>
                    <input type="text" id="iban" name="iban" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
            </div>
            
            <div class="mb-4">
                <label for="bic" class="block text-gray-700 text-sm font-bold mb-2">BIC</label>
                <input type="text" id="bic" name="bic" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Mettre à jour les informations bancaires
            </button>
        </form>
    </div>
</div>

<?php include '../php/footer.php'; ?>
