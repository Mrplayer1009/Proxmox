>
<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_type = isset($_SESSION['is_prestataire']) && $_SESSION['is_prestataire'] == 1 ? 'prestataire' : 'client';

$user_response = $api_client->get('users', ['id' => $user_id]);
$user_data = $user_response['data'] ?? [];

if ($user_data && $user_data['banni'] == 1) {
    header('Location: account_suspended.php');
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: agenda.php');
    exit;
}

$intervention_id = intval($_GET['id']);

$params = [
    'id' => $intervention_id
];

if ($user_type === 'prestataire') {
    $params['prestataire_id'] = $user_id;
} else {
    $params['client_id'] = $user_id;
}

$intervention_response = $api_client->get('interventions', $params);
$intervention = $intervention_response['data'] ?? null;

if (!$intervention) {
    header('Location: agenda.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    if ($user_type === 'prestataire') {
        $new_status = $_POST['status'];
        $commentaire = $_POST['commentaire'] ?? null;
        
        $valid_statuses = ['planifiée', 'en cours', 'terminée', 'annulée'];
        if (in_array($new_status, $valid_statuses)) {
            $update_response = $api_client->put('interventions', [
                'statut' => $new_status,
                'commentaires' => $commentaire
            ], ['id' => $intervention_id]);
            
            if ($update_response['status'] === 'success') {
                $intervention['statut'] = $new_status;
                $intervention['commentaires'] = $commentaire;
                
                $success_message = "Le statut de l'intervention a été mis à jour avec succès.";
            } else {
                $error_message = "Une erreur est survenue lors de la mise à jour du statut.";
            }
        } else {
            $error_message = "Le statut sélectionné n'est pas valide.";
        }
    } else {
        $error_message = "Seuls les prestataires peuvent modifier le statut de l'intervention.";
    }
}

$has_evaluation = false;
if ($user_type === 'client') {
    $eval_response = $api_client->get('evaluations', [
        'client_id' => $user_id,
        'intervention_id' => $intervention_id
    ]);
    $has_evaluation = !empty($eval_response['data']);
}

// Formater les dates et heures
$date_intervention = date('d/m/Y', strtotime($intervention['date_intervention']));
$heure_debut = date('H:i', strtotime($intervention['heure_debut']));
$duree_minutes = $intervention['duree'];
$heures = floor($duree_minutes / 60);
$minutes = $duree_minutes % 60;

if ($heures > 0) {
    $duree_formatted = $heures . 'h';
    if ($minutes > 0) $duree_formatted .= $minutes . 'min';
} else {
    $duree_formatted = $minutes . ' min';
}

$heure_fin = date('H:i', strtotime($intervention['heure_debut'] . ' + ' . $duree_minutes . ' minutes'));

// Statuts avec leurs couleurs associées
$status_colors = [
    'planifiée' => 'bg-yellow-100 border-yellow-400 text-yellow-800',
    'en cours' => 'bg-blue-100 border-blue-400 text-blue-800',
    'terminée' => 'bg-green-100 border-green-400 text-green-800',
    'annulée' => 'bg-red-100 border-red-400 text-red-800'
];
$status_class = $status_colors[$intervention['statut']] ?? 'bg-gray-100 border-gray-400 text-gray-800';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'intervention - Plateforme de Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include 'headerp.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center mb-6">
            <a href="agenda.php" class="text-blue-500 hover:text-blue-700 mr-4">
                <i class="fas fa-arrow-left"></i> Retour à l'agenda
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Détails de l'intervention</h1>
        </div>
        
        <?php if (isset($success_message)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p><?php echo $success_message; ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- En-tête d'intervention -->
            <div class="bg-gray-50 p-6 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">
                            <?php echo htmlspecialchars($intervention['type_service']); ?>
                        </h2>
                        <p class="text-gray-600 mt-1">
                            <?php echo $date_intervention; ?> • <?php echo $heure_debut; ?> - <?php echo $heure_fin; ?> (<?php echo $duree_formatted; ?>)
                        </p>
                    </div>
                    
                    <div class="mt-4 md:mt-0">
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-medium <?php echo $status_class; ?>">
                            <?php echo ucfirst($intervention['statut']); ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Contenu principal -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Détails de l'intervention -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Détails de l'intervention</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Service</p>
                                <p class="font-medium"><?php echo htmlspecialchars($intervention['type_service']); ?></p>
                            </div>
                            
                            <?php if (!empty($intervention['service_description'])): ?>
                                <div>
                                    <p class="text-sm text-gray-600">Description du service</p>
                                    <p><?php echo nl2br(htmlspecialchars($intervention['service_description'])); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div>
                                <p class="text-sm text-gray-600">Date et heure</p>
                                <p class="font-medium">
                                    <?php echo $date_intervention; ?> à <?php echo $heure_debut; ?>
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600">Durée prévue</p>
                                <p class="font-medium"><?php echo $duree_formatted; ?></p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600">Adresse d'intervention</p>
                                <p class="font-medium">
                                    <?php echo !empty($intervention['adresse_intervention']) ? nl2br(htmlspecialchars($intervention['adresse_intervention'])) : 'Non spécifiée'; ?>
                                </p>
                            </div>
                            
                            <?php if (!empty($intervention['commentaires'])): ?>
                                <div>
                                    <p class="text-sm text-gray-600">Commentaires</p>
                                    <p class="italic"><?php echo nl2br(htmlspecialchars($intervention['commentaires'])); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Informations de contact -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <?php echo $user_type === 'prestataire' ? 'Informations du client' : 'Informations du prestataire'; ?>
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Nom</p>
                                <p class="font-medium">
                                    <?php if ($user_type === 'prestataire'): ?>
                                        <?php echo htmlspecialchars($intervention['client_prenom'] . ' ' . $intervention['client_nom']); ?>
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($intervention['prestataire_prenom'] . ' ' . $intervention['prestataire_nom']); ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-medium">
                                    <?php if ($user_type === 'prestataire'): ?>
                                        <a href="mailto:<?php echo htmlspecialchars($intervention['client_email']); ?>" class="text-blue-500 hover:text-blue-700">
                                            <?php echo htmlspecialchars($intervention['client_email']); ?>
                                        </a>
                                    <?php else: ?>
                                        <a href="mailto:<?php echo htmlspecialchars($intervention['prestataire_email']); ?>" class="text-blue-500 hover:text-blue-700">
                                            <?php echo htmlspecialchars($intervention['prestataire_email']); ?>
                                        </a>
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600">Téléphone</p>
                                <p class="font-medium">
                                    <?php if ($user_type === 'prestataire'): ?>
                                        <a href="tel:<?php echo htmlspecialchars($intervention['client_telephone']); ?>" class="text-blue-500 hover:text-blue-700">
                                            <?php echo htmlspecialchars($intervention['client_telephone']); ?>
                                        </a>
                                    <?php else: ?>
                                        <a href="tel:<?php echo htmlspecialchars($intervention['prestataire_telephone']); ?>" class="text-blue-500 hover:text-blue-700">
                                            <?php echo htmlspecialchars($intervention['prestataire_telephone']); ?>
                                        </a>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        
                        <?php if ($user_type === 'prestataire' && $intervention['statut'] !== 'annulée'): ?>
                            <!-- Formulaire de mise à jour du statut (visible uniquement par le prestataire) -->
                            <div class="mt-8">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Mettre à jour le statut</h3>
                                
                                <form method="POST" action="">
                                    <div class="mb-4">
                                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                                        <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            <option value="planifiée" <?php echo $intervention['statut'] === 'planifiée' ? 'selected' : ''; ?>>Planifiée</option>
                                            <option value="en cours" <?php echo $intervention['statut'] === 'en cours' ? 'selected' : ''; ?>>En cours</option>
                                            <option value="terminée" <?php echo $intervention['statut'] === 'terminée' ? 'selected' : ''; ?>>Terminée</option>
                                            <option value="annulée" <?php echo $intervention['statut'] === 'annulée' ? 'selected' : ''; ?>>Annulée</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-1">Commentaire</label>
                                        <textarea id="commentaire" name="commentaire" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Ajouter un commentaire (facultatif)"><?php echo htmlspecialchars($intervention['commentaires'] ?? ''); ?></textarea>
                                    </div>
                                    
                                    <button type="submit" name="update_status" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md transition duration-150 ease-in-out">
                                        Mettre à jour
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($user_type === 'client' && $intervention['statut'] === 'terminée' && !$has_evaluation): ?>
                            <!-- Bouton pour évaluer l'intervention (visible uniquement par le client pour les interventions terminées) -->
                            <div class="mt-8">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Évaluation</h3>
                                <p class="text-gray-600 mb-4">Cette intervention est terminée. N'oubliez pas de laisser une évaluation pour le prestataire.</p>
                                
                                <a href="evaluation.php?intervention_id=<?php echo $intervention_id; ?>&prestataire_id=<?php echo $intervention['prestataire_id']; ?>" class="inline-block bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md transition duration-150 ease-in-out">
                                    Évaluer cette intervention
                                </a>
                            </div>
                        <?php elseif ($user_type === 'client' && $intervention['statut'] === 'terminée' && $has_evaluation): ?>
                            <div class="mt-8">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Évaluation</h3>
                                <p class="text-gray-600">Vous avez déjà évalué cette intervention.</p>
                                
                                <a href="evaluation.php" class="inline-block mt-2 text-blue-500 hover:text-blue-700">
                                    Voir toutes mes évaluations
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
