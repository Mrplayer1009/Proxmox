<?php
session_start();
require_once '../php/verif.php';
require_once '../php/api_client.php';

// Check if user is logged in and has the prestataire role
if (!isset($_SESSION['user_id']) || !in_array('prestataire', $_SESSION['roles'])) {
    header('Location: ../index.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Handle form submission for new availability
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_availability') {
    $data = [
        'user_id' => $userId,
        'date_debut' => $_POST['date_debut'],
        'date_fin' => $_POST['date_fin'],
        'adresse' => $_POST['adresse'],
        'type_service' => $_POST['type_service'],
        'statut' => 'available'
    ];
    
    $result = apiRequest("POST", "disponibilites", $data);
    
    if ($result && isset($result['id'])) {
        $successMessage = "Disponibilité ajoutée avec succès.";
    } else {
        $errorMessage = "Erreur lors de l'ajout de la disponibilité.";
    }
}

// Handle delete request
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $disponibiliteId = $_GET['delete'];
    $result = apiRequest("DELETE", "disponibilites/$disponibiliteId", null);
    
    if ($result && isset($result['success']) && $result['success']) {
        $successMessage = "Disponibilité supprimée avec succès.";
    } else {
        $errorMessage = "Erreur lors de la suppression de la disponibilité.";
    }
}

// Get availability data from API
$disponibilites = apiRequest("GET", "disponibilites?user_id=$userId", null);

// Get upcoming interventions
$upcomingInterventions = apiRequest("GET", "interventions?prestataire_id=$userId&status=pending,in_progress", null);

// Get service types
$serviceTypes = apiRequest("GET", "services", null);

include 'header_prestataire.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Mon Agenda</h1>
    
    <?php if (isset($successMessage)): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p><?= $successMessage ?></p>
        </div>
    <?php endif; ?>
    
    <?php if (isset($errorMessage)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p><?= $errorMessage ?></p>
        </div>
    <?php endif; ?>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Upcoming Interventions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Interventions à venir</h2>
            
            <?php if (empty($upcomingInterventions)): ?>
                <p class="text-gray-500">Aucune intervention à venir.</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($upcomingInterventions as $intervention): ?>
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold"><?= date('d/m/Y', strtotime($intervention['date_intervention'])) ?></p>
                                    <p class="text-sm text-gray-600"><?= htmlspecialchars($intervention['adresse'] ?? '') ?></p>
                                    <p class="text-sm text-gray-600">Type: <?= htmlspecialchars($intervention['type_service'] ?? '') ?></p>
                                </div>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= ($intervention['status'] ?? '') === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' ?>">
                                    <?= ($intervention['status'] ?? '') === 'pending' ? 'En attente' : 'En cours' ?>
                                </span>
                            </div>
                            <a href="voir_intervention.php?id=<?= $intervention['id'] ?? '' ?>" class="text-blue-600 hover:underline text-sm">Voir détails</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Add Availability Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Ajouter une disponibilité</h2>
            
            <form method="POST" action="">
                <input type="hidden" name="action" value="add_availability">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="date_debut" class="block text-gray-700 text-sm font-bold mb-2">Date de début</label>
                        <input type="datetime-local" id="date_debut" name="date_debut" required 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    
                    <div>
                        <label for="date_fin" class="block text-gray-700 text-sm font-bold mb-2">Date de fin</label>
                        <input type="datetime-local" id="date_fin" name="date_fin" required 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="adresse" class="block text-gray-700 text-sm font-bold mb-2">Adresse</label>
                    <input type="text" id="adresse" name="adresse" required 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                
                <div class="mb-4">
                    <label for="type_service" class="block text-gray-700 text-sm font-bold mb-2">Type de service</label>
                    <select id="type_service" name="type_service" required 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Sélectionnez un type de service</option>
                        <?php if (!empty($serviceTypes)): ?>
                            <?php foreach ($serviceTypes as $service): ?>
                                <option value="<?= htmlspecialchars($service['id']) ?>"><?= htmlspecialchars($service['nom']) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="entretien">Entretien</option>
                            <option value="reparation">Réparation</option>
                            <option value="installation">Installation</option>
                            <option value="conseil">Conseil</option>
                        <?php endif; ?>
                    </select>
                </div>
                
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Ajouter la disponibilité
                </button>
            </form>
        </div>
    </div>
    
    <!-- Availability Calendar -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Mes disponibilités</h2>
        
        <?php if (empty($disponibilites)): ?>
            <p class="text-gray-500">Aucune disponibilité enregistrée.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date de début</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date de fin</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Adresse</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type de service</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($disponibilites as $disponibilite): ?>
                            <tr>
                                <td class="py-3 px-4 border-b border-gray-200"><?= date('d/m/Y H:i', strtotime($disponibilite['date_debut'])) ?></td>
                                <td class="py-3 px-4 border-b border-gray-200"><?= date('d/m/Y H:i', strtotime($disponibilite['date_fin'])) ?></td>
                                <td class="py-3 px-4 border-b border-gray-200"><?= htmlspecialchars($disponibilite['adresse'] ?? '') ?></td>
                                <td class="py-3 px-4 border-b border-gray-200"><?= htmlspecialchars($disponibilite['type_service'] ?? '') ?></td>
                                <td class="py-3 px-4 border-b border-gray-200">
                                    <?php if (($disponibilite['statut'] ?? '') === 'available'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Disponible
                                        </span>
                                    <?php elseif (($disponibilite['statut'] ?? '') === 'booked'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Réservé
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 border-b border-gray-200">
                                    <?php if (($disponibilite['statut'] ?? '') === 'available'): ?>
                                        <a href="agenda.php?delete=<?= $disponibilite['id'] ?? '' ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette disponibilité ?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Calendar View -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Vue calendrier</h2>
        <div id="calendar" class="min-h-[400px]">
            <!-- Calendar will be rendered here -->
            <p class="text-center text-gray-500">Chargement du calendrier...</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css">
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale: 'fr',
            events: [
                <?php if (!empty($disponibilites)): ?>
                    <?php foreach ($disponibilites as $disponibilite): ?>
                        {
                            title: '<?= htmlspecialchars($disponibilite['type_service'] ?? 'Disponible') ?>',
                            start: '<?= $disponibilite['date_debut'] ?>',
                            end: '<?= $disponibilite['date_fin'] ?>',
                            color: '<?= ($disponibilite['statut'] ?? '') === 'available' ? '#10B981' : '#3B82F6' ?>',
                            extendedProps: {
                                status: '<?= $disponibilite['statut'] ?? 'available' ?>',
                                address: '<?= htmlspecialchars($disponibilite['adresse'] ?? '') ?>'
                            }
                        },
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <?php if (!empty($upcomingInterventions)): ?>
                    <?php foreach ($upcomingInterventions as $intervention): ?>
                        {
                            title: 'Intervention: <?= htmlspecialchars($intervention['type_service'] ?? 'Service') ?>',
                            start: '<?= $intervention['date_intervention'] ?? '' ?> <?= $intervention['heure_intervention'] ?? '09:00:00' ?>',
                            end: '<?= $intervention['date_intervention'] ?? '' ?> <?= isset($intervention['heure_fin']) ? $intervention['heure_fin'] : (isset($intervention['heure_intervention']) ? date('H:i:s', strtotime($intervention['heure_intervention']) + 3600) : '10:00:00') ?>',
                            color: '#EF4444',
                            extendedProps: {
                                status: '<?= $intervention['status'] ?? 'pending' ?>',
                                address: '<?= htmlspecialchars($intervention['adresse'] ?? '') ?>',
                                client: '<?= htmlspecialchars(($intervention['client_prenom'] ?? '') . ' ' . ($intervention['client_nom'] ?? '')) ?>'
                            }
                        },
                    <?php endforeach; ?>
                <?php endif; ?>
            ],
            eventClick: function(info) {
                alert('Détails:\n' + 
                      'Titre: ' + info.event.title + '\n' +
                      'Début: ' + info.event.start.toLocaleString() + '\n' +
                      'Fin: ' + (info.event.end ? info.event.end.toLocaleString() : 'Non spécifié') + '\n' +
                      'Statut: ' + info.event.extendedProps.status + '\n' +
                      'Adresse: ' + info.event.extendedProps.address + '\n' +
                      (info.event.extendedProps.client ? 'Client: ' + info.event.extendedProps.client : '')
                );
            }
        });
        calendar.render();
    });
</script>
