<?php
session_start();
include 'db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'] ?? '';

// Traitement du formulaire d'évaluation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['evaluation_submit'])) {
    $prestataire_id = $_POST['prestataire_id'];
    $client_id = $user_id;
    $intervention_id = $_POST['intervention_id'];
    $note = $_POST['note'];
    $commentaire = $_POST['commentaire'];
    
    $result = $api_client->post('evaluations', [
        'prestataire_id' => $prestataire_id,
        'client_id' => $client_id,
        'intervention_id' => $intervention_id,
        'note' => $note,
        'commentaire' => $commentaire
    ]);
    
    if ($result['status'] === 'success') {
        $success_message = "Votre évaluation a été soumise avec succès.";
    } else {
        $error_message = "Une erreur est survenue lors de la soumission de votre évaluation.";
    }
}

// Récupérer les évaluations selon le type d'utilisateur
if ($user_type === 'prestataire') {
    // Récupérer les évaluations reçues par le prestataire
    $evaluations_response = $api_client->get('evaluations', ['prestataire_id' => $user_id]);
    $evaluations = $evaluations_response['data'] ?? [];
    
    // Calculer la note moyenne
    $rating_response = $api_client->get('evaluations', ['prestataire_id' => $user_id, 'action' => 'rating']);
    $rating = $rating_response['data'] ?? ['note_moyenne' => 0, 'nombre_evaluations' => 0];
} else {
    // Récupérer les évaluations données par le client
    $evaluations_response = $api_client->get('evaluations', ['client_id' => $user_id]);
    $evaluations = $evaluations_response['data'] ?? [];
    
    // Récupérer les interventions terminées qui n'ont pas encore été évaluées
    $interventions_response = $api_client->get('interventions', [
        'client_id' => $user_id,
        'action' => 'to_evaluate'
    ]);
    $interventions_a_evaluer = $interventions_response['data'] ?? [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluations - Plateforme de Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include 'headerp.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            <?php echo $user_type === 'prestataire' ? 'Mes évaluations reçues' : 'Mes évaluations données'; ?>
        </h1>
        
        <?php if (isset($success_message)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p><?php echo $success_message; ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>
        
        <?php if ($user_type === 'prestataire' && isset($rating)): ?>
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Ma note moyenne</h2>
                <div class="flex items-center mb-2">
                    <div class="flex items-center">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= round($rating['note_moyenne'])): ?>
                                <i class="fas fa-star text-yellow-400 text-xl"></i>
                            <?php else: ?>
                                <i class="far fa-star text-yellow-400 text-xl"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <span class="ml-2 text-gray-700 font-medium">
                        <?php echo number_format($rating['note_moyenne'], 1); ?>/5
                    </span>
                </div>
                <p class="text-gray-600">
                    Basé sur <?php echo $rating['nombre_evaluations']; ?> évaluation(s)
                </p>
            </div>
        <?php endif; ?>
        
        <?php if ($user_type === 'client' && !empty($interventions_a_evaluer)): ?>
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Interventions à évaluer</h2>
                
                <?php foreach ($interventions_a_evaluer as $intervention): ?>
                    <div class="border-b border-gray-200 py-4 last:border-0">
                        <h3 class="font-medium text-gray-800">
                            <?php echo htmlspecialchars($intervention['type_service']); ?> avec 
                            <?php echo htmlspecialchars($intervention['prestataire_prenom'] . ' ' . $intervention['prestataire_nom']); ?>
                        </h3>
                        <p class="text-gray-600 text-sm mb-2">
                            Le <?php echo date('d/m/Y', strtotime($intervention['date_intervention'])); ?>
                        </p>
                        
                        <button class="mt-2 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md text-sm transition duration-150 ease-in-out"
                                onclick="openEvaluationModal(<?php echo $intervention['id']; ?>, <?php echo $intervention['prestataire_id']; ?>)">
                            Évaluer cette intervention
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Modal pour ajouter une évaluation -->
            <div id="evaluationModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-800">Évaluer l'intervention</h3>
                        <button onclick="closeEvaluationModal()" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <form method="POST" action="evaluation.php">
                        <input type="hidden" name="intervention_id" id="intervention_id" value="">
                        <input type="hidden" name="prestataire_id" id="prestataire_id" value="">
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Note</label>
                            <div class="flex space-x-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="note" value="<?php echo $i; ?>" class="hidden peer" required <?php echo $i === 5 ? 'checked' : ''; ?>>
                                        <i class="far fa-star text-2xl text-yellow-400 peer-checked:fas"></i>
                                    </label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="commentaire" class="block text-gray-700 text-sm font-medium mb-2">Commentaire</label>
                            <textarea id="commentaire" name="commentaire" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Partagez votre expérience..."></textarea>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="button" onclick="closeEvaluationModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-md mr-2 transition duration-150 ease-in-out">
                                Annuler
                            </button>
                            <button type="submit" name="evaluation_submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md transition duration-150 ease-in-out">
                                Soumettre
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Liste des évaluations -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <?php echo $user_type === 'prestataire' ? 'Évaluations reçues' : 'Évaluations données'; ?>
            </h2>
            
            <?php if (empty($evaluations)): ?>
                <p class="text-gray-600">Aucune évaluation pour le moment.</p>
            <?php else: ?>
                <?php foreach ($evaluations as $evaluation): ?>
                    <div class="border-b border-gray-200 py-4 last:border-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium text-gray-800">
                                    <?php if ($user_type === 'prestataire'): ?>
                                        Évaluation de <?php echo htmlspecialchars($evaluation['client_prenom'] . ' ' . $evaluation['client_nom']); ?>
                                    <?php else: ?>
                                        Évaluation pour <?php echo htmlspecialchars($evaluation['prestataire_prenom'] . ' ' . $evaluation['prestataire_nom']); ?>
                                    <?php endif; ?>
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    <?php if (!empty($evaluation['type_service'])): ?>
                                        Service: <?php echo htmlspecialchars($evaluation['type_service']); ?> -
                                    <?php endif; ?>
                                    <?php if (!empty($evaluation['date_intervention'])): ?>
                                        Intervention du <?php echo date('d/m/Y', strtotime($evaluation['date_intervention'])); ?> -
                                    <?php endif; ?>
                                    Évalué le <?php echo date('d/m/Y', strtotime($evaluation['date_evaluation'])); ?>
                                </p>
                            </div>
                            <div class="flex items-center">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= $evaluation['note']): ?>
                                        <i class="fas fa-star text-yellow-400"></i>
                                    <?php else: ?>
                                        <i class="far fa-star text-yellow-400"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <span class="ml-1 text-gray-700 font-medium">
                                    <?php echo number_format($evaluation['note'], 1); ?>
                                </span>
                            </div>
                        </div>
                        <?php if (!empty($evaluation['commentaire'])): ?>
                            <p class="mt-2 text-gray-700"><?php echo nl2br(htmlspecialchars($evaluation['commentaire'])); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
    
    <script>
        function openEvaluationModal(interventionId, prestataireId) {
            document.getElementById('intervention_id').value = interventionId;
            document.getElementById('prestataire_id').value = prestataireId;
            document.getElementById('evaluationModal').classList.remove('hidden');
        }
        
        function closeEvaluationModal() {
            document.getElementById('evaluationModal').classList.add('hidden');
        }
        
        // Gestion des étoiles interactives dans le modal
        document.querySelectorAll('input[name="note"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const value = this.value;
                document.querySelectorAll('input[name="note"]').forEach(function(r, index) {
                    const star = r.nextElementSibling;
                    if (index < value) {
                        star.classList.remove('far');
                        star.classList.add('fas');
                    } else {
                        star.classList.remove('fas');
                        star.classList.add('far');
                    }
                });
            });
        });
    </script>
</body>
</html>
