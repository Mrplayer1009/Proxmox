<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id_user = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $type = $_POST['type'];

    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        // Préparer les données pour l'API
        $file_data = file_get_contents($_FILES['document']['tmp_name']);
        $file_name = basename($_FILES['document']['name']);
        
        // Créer un formulaire multipart pour l'API
        $boundary = uniqid();
        $delimiter = '-------------' . $boundary;
        
        $post_data = '';
        
        // Ajouter les champs texte
        $post_data .= "--" . $delimiter . "\r\n";
        $post_data .= 'Content-Disposition: form-data; name="user_id"' . "\r\n\r\n";
        $post_data .= $id_user . "\r\n";
        
        $post_data .= "--" . $delimiter . "\r\n";
        $post_data .= 'Content-Disposition: form-data; name="description"' . "\r\n\r\n";
        $post_data .= $description . "\r\n";
        
        $post_data .= "--" . $delimiter . "\r\n";
        $post_data .= 'Content-Disposition: form-data; name="type"' . "\r\n\r\n";
        $post_data .= $type . "\r\n";
        
        // Ajouter le fichier
        $post_data .= "--" . $delimiter . "\r\n";
        $post_data .= 'Content-Disposition: form-data; name="document"; filename="' . $file_name . '"' . "\r\n";
        $post_data .= 'Content-Type: ' . mime_content_type($_FILES['document']['tmp_name']) . "\r\n\r\n";
        $post_data .= $file_data . "\r\n";
        $post_data .= "--" . $delimiter . "--\r\n";
        
        // Configurer la requête cURL
        $ch = curl_init('../api/api.php?route=documents');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: multipart/form-data; boundary=' . $delimiter,
            'Content-Length: ' . strlen($post_data)
        ]);
        
        // Exécuter la requête
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code == 201) {
            $success_message = "Document uploadé avec succès !";
        } else {
            $error_message = "Erreur lors de l'upload.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Uploader un document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-xl mx-auto bg-white p-8 rounded shadow">
        <h1 class="text-2xl font-bold mb-6 text-center">Téléversement de document</h1>
        
        <?php if (isset($success_message)): ?>
            <p class="text-green-500 text-center mt-4"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <p class="text-red-500 text-center mt-4"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block font-semibold">Description :</label>
                <textarea name="description" required class="w-full border p-2 rounded"></textarea>
            </div>
            <div>
                <label class="block font-semibold">Type :</label>
                <select name="type" required class="w-full border p-2 rounded">
                    <option value="Prestataire">Prestataire</option>
                    <option value="Commerçant">Commerçant</option>
                    <option value="Livreur">Livreur</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold">Fichier :</label>
                <input type="file" name="document" required class="w-full border p-2 rounded">
            </div>
            <div class="text-center">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Envoyer
                </button>
            </div>
        </form>
    </div>
</body>
</html>
