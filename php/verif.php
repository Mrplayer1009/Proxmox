<?php
session_start();
include 'db.php';

// Traitement des validations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doc_id = $_POST['doc_id'];
    $action = $_POST['action'];

    $stmt = $pdo->prepare("SELECT * FROM documents WHERE id = ?");
    $stmt->execute([$doc_id]);
    $doc = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($doc) {
        if ($action === 'accepter') {
            $type = strtolower($doc['type']); // prestataire, commerçant, livreur
            $col = ($type === 'commerçant') ? 'is_commercant' : "is_$type";
            $pdo->prepare("UPDATE users SET $col = 1 WHERE id = ?")->execute([$doc['id_user']]);
            $pdo->prepare("UPDATE documents SET statut = 'accepté' WHERE id = ?")->execute([$doc_id]);
        } elseif ($action === 'refuser') {
            $pdo->prepare("UPDATE documents SET statut = 'refusé' WHERE id = ?")->execute([$doc_id]);
        }
    }
}

// Récupération des documents en attente
$stmt = $pdo->query("SELECT d.*, u.nom, u.prenom FROM documents d JOIN users u ON d.id_user = u.id WHERE d.statut = 'en_attente'");
$docs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification des Documents</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto bg-white p-8 rounded shadow">
        <h1 class="text-3xl font-bold mb-6 text-center">Vérification des Documents</h1>
        <?php if (empty($docs)) : ?>
            <p class="text-center text-gray-600">Aucune demande en attente.</p>
        <?php else : ?>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Utilisateur</th>
                    <th class="border p-2">Type</th>
                    <th class="border p-2">Description</th>
                    <th class="border p-2">Fichier</th>
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($docs as $doc) : ?>
                    <tr>
                        <td class="border p-2"><?= htmlspecialchars($doc['prenom'] . ' ' . $doc['nom']) ?></td>
                        <td class="border p-2"><?= $doc['type'] ?></td>
                        <td class="border p-2"><?= htmlspecialchars($doc['description']) ?></td>
                        <td class="border p-2">
                            <a href="<?= htmlspecialchars($doc['file_path']) ?>" target="_blank" class="text-blue-600 underline">Voir</a>
                        </td>
                        <td class="border p-2">
                            <form method="post" class="flex gap-2">
                                <input type="hidden" name="doc_id" value="<?= $doc['id'] ?>">
                                <button name="action" value="accepter" class="bg-green-500 text-white px-4 py-1 rounded hover:bg-green-600">Accepter</button>
                                <button name="action" value="refuser" class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600">Refuser</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</body>
</html>
