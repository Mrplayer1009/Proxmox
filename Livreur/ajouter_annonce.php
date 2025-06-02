<?php
session_start();
require_once '../php/verif.php';
require_once '../php/api_client.php';

if (!isset($_SESSION['user_id']) || !in_array('livreur', $_SESSION['roles'])) {
    header('Location: ../index.php');
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'user_id' => $userId,
        'titre' => $_POST['titre'],
        'description' => $_POST['description'],
        'lieu_depart' => $_POST['lieu_depart'],
        'lieu_arrivee' => $_POST['lieu_arrivee'],
        'date' => $_POST['date'],
        'type' => $_POST['type'],
        'prix' => $_POST['prix'],
        'statut' => 'active'
    ];
    
    $result = apiRequest("POST", "annonce", $data);
    
    if ($result && isset($result['id'])) {
        header('Location: annonces.php?success=1');
        exit;
    } else {
        $errorMessage = "Erreur lors de la création de l'annonce.";
    }
}

include 'header_livreur.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Ajouter une annonce</h1>
        
        <?php if (isset($errorMessage)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p><?= $errorMessage ?></p>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-4">
                <label for="titre" class="block text-gray-700 text-sm font-bold mb-2">Titre</label>
                <input type="text" id="titre" name="titre" required 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <textarea id="description" name="description" rows="4" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="lieu_depart" class="block text-gray-700 text-sm font-bold mb-2">Lieu de départ</label>
                    <input type="text" id="lieu_depart" name="lieu_depart" required 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                
                <div>
                    <label for="lieu_arrivee" class="block text-gray-700 text-sm font-bold mb-2">Lieu d'arrivée</label>
                    <input type="text" id="lieu_arrivee" name="lieu_arrivee" required 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date</label>
                    <input type="date" id="date" name="date" required 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                
                <div>
                    <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Type de transport</label>
                    <select id="type" name="type" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Sélectionnez un type</option>
                        <option value="Colis">Colis</option>
                        <option value="Meuble">Meuble</option>
                        <option value="Electroménager">Electroménager</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>
                
                <div>
                    <label for="prix" class="block text-gray-700 text-sm font-bold mb-2">Prix (€)</label>
                    <input type="number" id="prix" name="prix" min="0" step="0.01" required 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
            </div>
            
            <div class="flex items-center justify-between mt-6">
                <a href="annonces.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Annuler
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Publier l'annonce
                </button>
            </div>
        </form>
    </div>
</div>

<?php include '../php/footer.php'; ?>
