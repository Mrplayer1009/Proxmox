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

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add_availability') {
        $date = $_POST['date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        
        if (empty($date) || empty($start_time) || empty($end_time)) {
            $error = "Tous les champs sont obligatoires.";
        } elseif ($start_time >= $end_time) {
            $error = "L'heure de début doit être antérieure à l'heure de fin.";
        } else {
            $availability_data = [
                'prestataire_id' => $user_id,
                'date' => $date,
                'heure_debut' => $start_time,
                'heure_fin' => $end_time
            ];
            
            $response = $api_client->post('disponibilites', $availability_data);
            $result = json_decode($response, true);
            
            if (isset($result['status']) && $result['status'] === 'success') {
                $message = "Disponibilité ajoutée avec succès.";
            } else {
                $error = $result['message'] ?? "Erreur lors de l'ajout de la disponibilité.";
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete_availability') {
        $availability_id = $_POST['availability_id'];
        
        $response = $api_client->delete("disponibilites/$availability_id");
        $result = json_decode($response, true);
        
        if (isset($result['status']) && $result['status'] === 'success') {
            $message = "Disponibilité supprimée avec succès.";
        } else {
            $error = $result['message'] ?? "Erreur lors de la suppression de la disponibilité.";
        }
    }
}

// Get current availabilities
$response = $api_client->get("disponibilites?prestataire_id=$user_id");
$availabilities = json_decode($response, true) ?? [];

// Get upcoming interventions
$response = $api_client->get("interventions?prestataire_id=$user_id&status=pending");
$interventions = json_decode($response, true) ?? [];

// Prepare calendar data
$current_month = isset($_GET['month']) ? $_GET['month'] : date('m');
$current_year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$first_day = mktime(0, 0, 0, $current_month, 1, $current_year);
$days_in_month = date('t', $first_day);
$first_day_of_week = date('N', $first_day);

// Prepare availability data for calendar
$calendar_data = [];
for ($day = 1; $day <= $days_in_month; $day++) {
    $date = sprintf('%04d-%02d-%02d', $current_year, $current_month, $day);
    $calendar_data[$date] = [
        'availabilities' => [],
        'interventions' => []
    ];
}

// Add availabilities to calendar data
foreach ($availabilities as $availability) {
    $date = substr($availability['date'], 0, 10);
    if (isset($calendar_data[$date])) {
        $calendar_data[$date]['availabilities'][] = $availability;
    }
}

// Add interventions to calendar data
foreach ($interventions as $intervention) {
    $date = substr($intervention['date_intervention'], 0, 10);
    if (isset($calendar_data[$date])) {
        $calendar_data[$date]['interventions'][] = $intervention;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Disponibilités - EcoDeli</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include 'header_prestataire.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Mes Disponibilités</h1>
        
        <?php if (!empty($message)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p><?= $message ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p><?= $error ?></p>
            </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Add Availability Form -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Ajouter une disponibilité</h2>
                <form method="post" class="space-y-4">
                    <input type="hidden" name="action" value="add_availability">
                    
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" id="date" name="date" min="<?= date('Y-m-d') ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700">Heure de début</label>
                        <input type="time" id="start_time" name="start_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700">Heure de fin</label>
                        <input type="time" id="end_time" name="end_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Ajouter
                    </button>
                </form>
            </div>
            
            <!-- Calendar Navigation -->
            <div class="md:col-span-2 bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Calendrier - <?= date('F Y', $first_day) ?></h2>
                    <div class="flex space-x-2">
                        <?php
                        $prev_month = $current_month - 1;
                        $prev_year = $current_year;
                        if ($prev_month < 1) {
                            $prev_month = 12;
                            $prev_year--;
                        }
                        
                        $next_month = $current_month + 1;
                        $next_year = $current_year;
                        if ($next_month > 12) {
                            $next_month = 1;
                            $next_year++;
                        }
                        ?>
                        <a href="?month=<?= $prev_month ?>&year=<?= $prev_year ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded">
                            &laquo; Précédent
                        </a>
                        <a href="?month=<?= date('m') ?>&year=<?= date('Y') ?>" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                            Aujourd'hui
                        </a>
                        <a href="?month=<?= $next_month ?>&year=<?= $next_year ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded">
                            Suivant &raquo;
                        </a>
                    </div>
                </div>
                
                <!-- Calendar -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lun</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mar</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mer</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jeu</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ven</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sam</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dim</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $day_counter = 1;
                            $calendar_rows = ceil(($days_in_month + $first_day_of_week - 1) / 7);
                            
                            for ($row = 1; $row <= $calendar_rows; $row++) {
                                echo "<tr>";
                                
                                for ($col = 1; $col <= 7; $col++) {
                                    if (($row == 1 && $col < $first_day_of_week) || ($day_counter > $days_in_month)) {
                                        echo "<td class='px-6 py-4 whitespace-nowrap'></td>";
                                    } else {
                                        $date = sprintf('%04d-%02d-%02d', $current_year, $current_month, $day_counter);
                                        $today_class = ($date == date('Y-m-d')) ? 'bg-blue-50 border border-blue-200' : '';
                                        
                                        echo "<td class='px-6 py-4 whitespace-nowrap align-top $today_class'>";
                                        echo "<div class='font-medium text-gray-900'>$day_counter</div>";
                                        
                                        // Display availabilities
                                        if (!empty($calendar_data[$date]['availabilities'])) {
                                            echo "<div class='mt-2'>";
                                            foreach ($calendar_data[$date]['availabilities'] as $availability) {
                                                $start = date('H:i', strtotime($availability['heure_debut']));
                                                $end = date('H:i', strtotime($availability['heure_fin']));
                                                
                                                echo "<div class='bg-green-100 text-green-800 text-xs rounded px-2 py-1 mb-1 flex justify-between'>";
                                                echo "<span>$start - $end</span>";
                                                echo "<form method='post' class='inline'>";
                                                echo "<input type='hidden' name='action' value='delete_availability'>";
                                                echo "<input type='hidden' name='availability_id' value='{$availability['id']}'>";
                                                echo "<button type='submit' class='text-red-500 hover:text-red-700'>×</button>";
                                                echo "</form>";
                                                echo "</div>";
                                            }
                                            echo "</div>";
                                        }
                                        
                                        // Display interventions
                                        if (!empty($calendar_data[$date]['interventions'])) {
                                            echo "<div class='mt-2'>";
                                            foreach ($calendar_data[$date]['interventions'] as $intervention) {
                                                $time = date('H:i', strtotime($intervention['heure_intervention'] ?? $intervention['heure_debut']));
                                                $title = htmlspecialchars($intervention['titre'] ?? 'Intervention');
                                                
                                                echo "<div class='bg-blue-100 text-blue-800 text-xs rounded px-2 py-1 mb-1'>";
                                                echo "$time - $title";
                                                echo "</div>";
                                            }
                                            echo "</div>";
                                        }
                                        
                                        echo "</td>";
                                        $day_counter++;
                                    }
                                }
                                
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 flex space-x-4">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-100 rounded mr-2"></div>
                        <span class="text-sm text-gray-600">Disponibilité</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-100 rounded mr-2"></div>
                        <span class="text-sm text-gray-600">Intervention</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- List of Upcoming Availabilities -->
        <div class="mt-8 bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Mes prochaines disponibilités</h2>
            
            <?php
            // Filter availabilities to show only future ones
            $future_availabilities = array_filter($availabilities, function($availability) {
                return $availability['date'] >= date('Y-m-d');
            });
            
            // Sort by date and time
            usort($future_availabilities, function($a, $b) {
                if ($a['date'] == $b['date']) {
                    return $a['heure_debut'] <=> $b['heure_debut'];
                }
                return $a['date'] <=> $b['date'];
            });
            ?>
            
            <?php if (empty($future_availabilities)): ?>
                <p class="text-gray-500 text-center py-4">Aucune disponibilité future enregistrée</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure de début</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure de fin</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($future_availabilities as $availability): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('d/m/Y', strtotime($availability['date'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('H:i', strtotime($availability['heure_debut'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('H:i', strtotime($availability['heure_fin'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <form method="post" class="inline">
                                            <input type="hidden" name="action" value="delete_availability">
                                            <input type="hidden" name="availability_id" value="<?= $availability['id'] ?>">
                                            <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                        </form>
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
