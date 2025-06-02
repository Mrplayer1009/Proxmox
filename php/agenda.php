<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$user_data = $api_client->get('users', ['id' => $user_id]);
$user_data = $user_data['data'] ?? [];

if (empty($user_data)) {
    header('Location: dashboard.php');
    exit;
}

if ($user_data['is_prestataire'] != 1) {
    header('Location: dashboard.php');
    exit;
}

$current_month = isset($_GET['month']) ? intval($_GET['month']) : intval(date('m'));
$current_year = isset($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));

if ($current_month < 1 || $current_month > 12) {
    $current_month = date('m');
}

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

$days_in_month = cal_days_in_month(CAL_GREGORIAN, $current_month, $current_year);

$first_day_of_month = date('N', strtotime("$current_year-$current_month-01"));
if ($first_day_of_month == 7) {
    $first_day_of_month = 0; 
}

$start_date = "$current_year-$current_month-01";
$end_date = "$current_year-$current_month-$days_in_month";

$interventions_response = $api_client->get('interventions', [
    'action' => 'prestataire',
    'user_id' => $user_id,
    'start_date' => $start_date,
    'end_date' => $end_date
]);

$interventions = $interventions_response['data'] ?? [];

$interventions_by_day = [];
foreach ($interventions as $intervention) {
    $day = intval(date('j', strtotime($intervention['date_intervention'])));
    if (!isset($interventions_by_day[$day])) {
        $interventions_by_day[$day] = [];
    }
    $interventions_by_day[$day][] = $intervention;
}

$today = date('Y-m-d');
$three_months_later = date('Y-m-d', strtotime('+3 months'));

$upcoming_interventions_response = $api_client->get('interventions', [
    'action' => 'upcoming',
    'user_id' => $user_id
]);

$upcoming_interventions = $upcoming_interventions_response['data'] ?? [];

$months = [
    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
    9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
];

$days = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];

$active_view = isset($_GET['view']) ? $_GET['view'] : 'calendar';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda - Plateforme de Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include 'headerp.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Mon Agenda</h1>
        
        <div class="flex space-x-2 mb-6">
            <a href="?view=calendar&month=<?php echo $current_month; ?>&year=<?php echo $current_year; ?>" 
               class="py-2 px-4 rounded-md <?php echo $active_view === 'calendar' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100'; ?>">
                <i class="far fa-calendar-alt mr-1"></i> Calendrier
            </a>
            <a href="?view=list" 
               class="py-2 px-4 rounded-md <?php echo $active_view === 'list' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100'; ?>">
                <i class="fas fa-list mr-1"></i> Liste
            </a>
        </div>
        
        <?php if ($active_view === 'calendar'): ?>
            <!-- Vue Calendrier -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <a href="?view=calendar&month=<?php echo $prev_month; ?>&year=<?php echo $prev_year; ?>" class="flex items-center text-blue-500 hover:text-blue-700">
                        <i class="fas fa-chevron-left mr-1"></i>
                        <span class="hidden md:inline"><?php echo $months[$prev_month] . ' ' . $prev_year; ?></span>
                    </a>
                    
                    <h2 class="text-xl font-semibold text-gray-800">
                        <?php echo $months[$current_month] . ' ' . $current_year; ?>
                    </h2>
                    
                    <a href="?view=calendar&month=<?php echo $next_month; ?>&year=<?php echo $next_year; ?>" class="flex items-center text-blue-500 hover:text-blue-700">
                        <span class="hidden md:inline"><?php echo $months[$next_month] . ' ' . $next_year; ?></span>
                        <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>
                
                <!-- Grille du calendrier -->
                <div class="grid grid-cols-7 gap-2">
                    <?php
                    foreach ($days as $day) {
                        echo "<div class='text-center font-semibold text-gray-700'>$day</div>";
                    }
                    
                    for ($i = 0; $i < $first_day_of_month; $i++) {
                        echo "<div class='bg-gray-200 p-4 rounded'></div>";
                    }
                    
                    for ($day = 1; $day <= $days_in_month; $day++) {
                        $date_string = "$current_year-$current_month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                        $day_classes = "p-4 border rounded text-center";
                        $is_today = ($date_string === date('Y-m-d')) ? 'bg-blue-100' : 'bg-white';
                        echo "<div class='$day_classes $is_today'>
                                <span class='font-bold'>$day</span>";
                        
                        if (isset($interventions_by_day[$day])) {
                            foreach ($interventions_by_day[$day] as $intervention) {
                                echo "<div class='mt-2 p-2 bg-blue-500 text-white text-xs rounded'>";
                                echo htmlspecialchars($intervention['type_service']) . "<br>";
                                echo htmlspecialchars($intervention['heure_debut']) . " - " . htmlspecialchars($intervention['heure_fin']);
                                echo "</div>";
                            }
                        }
                        
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        <?php else: ?>
            <!-- Vue Liste -->
            <div class='bg-white rounded-lg shadow-md p-6'>
                <h2 class='text-xl font-semibold text-gray-800 mb-4'>Interventions à venir</h2>
                <ul class='divide-y divide-gray-200'>
                    <?php foreach ($upcoming_interventions as $intervention): ?>
                        <li class='py-4'>
                            <p class='text-gray-700 font-bold'>
                                <?php echo htmlspecialchars($intervention['type_service']); ?>
                            </p>
                            <p class='text-gray-500'>
                                <?php echo htmlspecialchars($intervention['date_intervention']) . " de " . htmlspecialchars($intervention['heure_debut']) . " à " . htmlspecialchars($intervention['heure_fin']); ?>
                            </p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
