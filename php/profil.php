<?php
session_start();
include 'db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Récupérer les informations de l'utilisateur
$user_id = $_SESSION['user_id'];
$sql = "SELECT nom, prenom, email FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Mettre à jour le mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $update_sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $pdo->prepare($update_sql);
    $stmt->execute([$new_password, $user_id]);
    $message = "Mot de passe mis à jour avec succès.";
}

// Récupérer les achats de l'utilisateur
$achat_sql = "SELECT id, nom_fichier, date_achat FROM pdf_achat WHERE user_id = ?";
$stmt = $pdo->prepare($achat_sql);
$stmt->execute([$user_id]);
$achats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<?php
include 'header.php';
?>
<body class="bg-gray-100">

    <div class="max-w-4xl mx-auto bg-white p-6 mt-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Mon Profil</h2>
        
        <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></p>
        <p><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>

        <!-- Formulaire de changement de mot de passe -->
        <h3 class="text-xl font-semibold mt-6">Changer mon mot de passe</h3>
        <?php if (isset($message)) : ?>
            <p class="text-green-500"><?= $message ?></p>
        <?php endif; ?>
        <form method="post" class="mt-4">
            <input type="password" name="new_password" required placeholder="Nouveau mot de passe" class="w-full p-2 border rounded">
            <input type="submit" value="Mettre à jour" class="bg-blue-500 text-white px-4 py-2 mt-2 rounded cursor-pointer">
        </form>
    </div>

    <!-- Liste des achats -->
    <div class="max-w-4xl mx-auto bg-white p-6 mt-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Mes Achats</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">ID</th>
                    <th class="border p-2">Nom du fichier</th>
                    <th class="border p-2">Date d'achat</th>
                    <th class="border p-2">Affichage</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($achats as $achat) : ?>
                    <tr>
                        <td class="border p-2"><?= htmlspecialchars($achat['id']) ?></td>
                        <td class="border p-2"><?= htmlspecialchars($achat['nom_fichier']) ?></td>
                        <td class="border p-2"><?= htmlspecialchars($achat['date_achat']) ?></td>
                        <td>
                        <a href="affiche_achat.php?id=<?= $achat['id']; ?>&user_id=<?= $_SESSION['user_id']; ?>" target="_blank">
                        <button class="btn">Afficher</button>
                        </a>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Liste des documents -->
<div class="max-w-4xl mx-auto bg-white p-6 mt-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4">Mes Documents</h2>
    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">ID</th>
                <th class="border p-2">Description</th>
                <th class="border p-2">Type</th>
                <th class="border p-2">Fichier</th>
                <th class="border p-2">Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->prepare("SELECT * FROM documents WHERE id_user = ?");
            $stmt->execute([$user_id]);
            $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($documents as $doc) :
            ?>
                <tr>
                    <td class="border p-2"><?= $doc['id'] ?></td>
                    <td class="border p-2"><?= htmlspecialchars($doc['description']) ?></td>
                    <td class="border p-2"><?= $doc['type'] ?></td>
                    <td class="border p-2">
                        <a href="<?= htmlspecialchars($doc['file_path']) ?>" target="_blank" class="text-blue-600 underline">Voir</a>
                    </td>
                    <td class="border p-2">
                        <?= isset($doc['statut']) ? htmlspecialchars($doc['statut']) : 'En attente' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

    </div>

    <footer class="bg-gray-800 text-white text-center p-4 mt-10">
        EcoDeli © 2025. Tous droits réservés.
    </footer>
</body>
</html>
