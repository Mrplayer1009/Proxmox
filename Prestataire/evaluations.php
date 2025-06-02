<?php
session_start();
require_once '../php/verif.php';
require_once '../php/api_client.php';

// Verify if user is logged in and has the prestataire role
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'prestataire') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$api_client = new ApiClient();

// Get all evaluations for this prestataire
$response = $api_client->get("evaluation?prestataire_id=$user_id");
$evaluations = json_decode($response, true);

// Calculate average rating
$total_rating = 0;
$rating_count = count($evaluations);
if ($rating_count > 0) {
    foreach ($evaluations as $evaluation) {
        $total_rating += $evaluation['note'];
    }
    $average_rating = $total_rating / $rating_count;
} else {
    $average_rating = 0;
}

// Group evaluations by month for chart data
$months = [];
$ratings_by_month = [];

if ($rating_count > 0) {
    foreach ($evaluations as $evaluation) {
        $month = date('M Y', strtotime($evaluation['date_evaluation']));
        if (!isset($ratings_by_month[$month])) {
            $ratings_by_month[$month] = [
                'count' => 0,
                'total' => 0
            ];
        }
        $ratings_by_month[$month]['count']++;
        $ratings_by_month[$month]['total'] += $evaluation['note'];
    }
    
    // Calculate average for each month
    foreach ($ratings_by_month as $month => $data) {
        $months[] = $month;
        $ratings_by_month[$month]['average'] = $data['total'] / $data['count'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluations - EcoDeli</title>
    <link href="../src/output.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <?php include 'header_prestataire.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Mes Évaluations</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Overall Rating Card -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Note moyenne globale</h2>
                <div class="flex items-center">
                    <span class="text-5xl font-bold text-yellow-500"><?= number_format($average_rating, 1) ?></span>
                    <span class="text-2xl text-gray-500 ml-2">/5</span>
                    <div class="ml-4 flex">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= round($average_rating)): ?>
                                <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            <?php else: ?>
                                <svg class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                </div>
                <p class="text-gray-500 mt-2">Basé sur <?= $rating_count ?> évaluations</p>
                
                <!-- Rating Distribution -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium mb-3">Répartition des notes</h3>
                    <?php
                    $rating_distribution = [0, 0, 0, 0, 0];
                    foreach ($evaluations as $evaluation) {
                        $rating_distribution[$evaluation['note'] - 1]++;
                    }
                    ?>
                    
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <?php 
                        $count = $rating_distribution[$i - 1];
                        $percentage = $rating_count > 0 ? ($count / $rating_count) * 100 : 0;
                        ?>
                        <div class="flex items-center mb-2">
                            <div class="w-12 text-sm text-gray-600"><?= $i ?> étoile<?= $i > 1 ? 's' : '' ?></div>
                            <div class="flex-1 mx-4">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-yellow-400 h-2.5 rounded-full" style="width: <?= $percentage ?>%"></div>
                                </div>
                            </div>
                            <div class="w-12 text-sm text-gray-600"><?= $count ?></div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <!-- Rating Trend Chart -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Évolution des notes</h2>
                <?php if (count($months) > 0): ?>
                    <canvas id="ratingTrendChart" height="250"></canvas>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-12">Pas assez de données pour afficher un graphique</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Recent Evaluations -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Toutes les évaluations</h2>
            
            <?php if (empty($evaluations)): ?>
                <p class="text-gray-500 text-center py-8">Aucune évaluation pour le moment</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commentaire</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($evaluations as $evaluation): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('d/m/Y', strtotime($evaluation['date_evaluation'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($evaluation['client_nom'] ?? 'Client') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($evaluation['service_nom'] ?? 'Service') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?php if ($i <= $evaluation['note']): ?>
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
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <?= htmlspecialchars($evaluation['commentaire']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if (count($months) > 0): ?>
    <script>
        // Chart for rating trends
        const ctx = document.getElementById('ratingTrendChart').getContext('2d');
        const ratingTrendChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_values($months)) ?>,
                datasets: [{
                    label: 'Note moyenne',
                    data: <?= json_encode(array_map(function($month) use ($ratings_by_month) {
                        return $ratings_by_month[$month]['average'];
                    }, $months)) ?>,
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 2,
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 0,
                        max: 5,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
