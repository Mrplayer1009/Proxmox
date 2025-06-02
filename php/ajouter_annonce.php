<?php
session_start();
include 'db.php';

if (!isset($_SESSION['is_livreur']) || $_SESSION['is_livreur'] != 1) {
    header("Location: ../index.php");
    exit();
}

$livreur_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $prix = floatval($_POST['prix']);

    if (!empty($titre) && !empty($description) && $prix > 0) {
        $result = $api_client->post('annonces', [
            'livreur_id' => $livreur_id,
            'titre' => $titre,
            'description' => $description,
            'prix' => $prix
        ]);
        
        if ($result['status'] === 'success') {
            $message = "Annonce ajoutée avec succès !";
        } else {
            $message = "Erreur lors de l'ajout: " . ($result['message'] ?? "Erreur inconnue");
        }
    } else {
        $message = "Veuillez remplir tous les champs et entrer un prix valide.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une annonce</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Ajouter une annonce</h1>
            <a href="gerer_annonces.php" class="bg-white text-blue-600 px-4 py-2 rounded-lg">Retour</a>
        </div>
    </nav>

    <div class="container mx-auto mt-10 p-6 bg-white shadow-md rounded-lg w-1/2">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Créer une nouvelle annonce</h2>

        <?php if ($message): ?>
            <p class="text-green-500"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form action="ajouter_annonce.php" method="POST" class="space-y-4">
            <div>
                <label for="titre" class="block text-gray-700">Titre de l'annonce :</label>
                <input type="text" id="titre" name="titre" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300" required>
            </div>

            <div>
                <label for="description" class="block text-gray-700">Description :</label>
                <textarea id="description" name="description" rows="4" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300" required></textarea>
            </div>

            <div>
                <label for="prix" class="block text-gray-700">Prix (€) :</label>
                <input type="number" id="prix" name="prix" min="0.01" step="0.01" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300" required>
            </div>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                Ajouter l'annonce
            </button>
        </form>
    </div>

</body>
</html>
