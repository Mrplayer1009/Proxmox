<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $is_livreur = isset($_POST['is_livreur']) ? 1 : 0;
    $is_prestataire = isset($_POST['is_prestataire']) ? 1 : 0;
    
    $sql = "INSERT INTO users (email, password, is_livreur, is_prestataire) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $password, $is_livreur, $is_prestataire]);
    echo "Inscription réussie !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col justify-center items-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Inscription</h2>
            <form method="post" class="space-y-4">
                <div>
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" class="w-full p-2 border rounded" placeholder="Placeholder" required>
                </div>
                <div>
                    <label class="block text-gray-700">Mot de passe</label>
                    <input type="password" name="password" class="w-full p-2 border rounded" placeholder="Placeholder" required>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"> Confirmez</button>

                <style>
                    /* From Uiverse.io by PriyanshuGupta28 */ 
.checkbox-wrapper input[type="checkbox"] {
  display: none;
}

.checkbox-wrapper .terms-label {
  cursor: pointer;
  display: flex;
  align-items: center;
}

.checkbox-wrapper .terms-label .label-text {
  margin-left: 10px;
}

.checkbox-wrapper .checkbox-svg {
  width: 30px;
  height: 30px;
}

.checkbox-wrapper .checkbox-box {
  fill: rgba(207, 205, 205, 0.425);
  stroke: #8c00ff;
  stroke-dasharray: 800;
  stroke-dashoffset: 800;
  transition: stroke-dashoffset 0.6s ease-in;
}

.checkbox-wrapper .checkbox-tick {
  stroke: #8c00ff;
  stroke-dasharray: 172;
  stroke-dashoffset: 172;
  transition: stroke-dashoffset 0.6s ease-in;
}

.checkbox-wrapper input[type="checkbox"]:checked + .terms-label .checkbox-box,
  .checkbox-wrapper input[type="checkbox"]:checked + .terms-label .checkbox-tick {
  stroke-dashoffset: 0;
}
</style>
