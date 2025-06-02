<?php
$host = 'localhost';
$db = 'ecodeli';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("INSERT INTO utilisateur (nom, prenom, email, password, type_utilisateur, date_inscription, statut_compte) 
                           VALUES (?, ?, ?, ?, 'client', NOW(), 'actif')");
    if ($stmt->execute([$nom, $prenom, $email, $password])) {
        $message = "Inscription réussie. <a href='login.php'>Se connecter</a>";
    } else {
        $message = "Erreur lors de l'inscription.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - EcoDeli</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<?php
include 'header.php';
?>
<body class="bg-gray-100">
    
    <div class="min-h-screen flex flex-col justify-center items-center py-12">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Inscription</h2>
            
            <?php if (!empty($error_message)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p><?php echo $error_message; ?></p>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success_message)): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p><?php echo $success_message; ?></p>
                </div>
            <?php endif; ?>
            
            <form method="post" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label for="prenom" class="block text-gray-700">Prénom</label>
                    <input type="text" id="prenom" name="prenom" class="w-full p-2 border rounded" required>
                </div>

                <div>
                    <label for="nom" class="block text-gray-700">Nom</label>
                    <input type="text" id="nom" name="nom" class="w-full p-2 border rounded" required>
                </div>

                <div>
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="w-full p-2 border rounded" required>
                </div>
                
                <div>
                    <label for="password" class="block text-gray-700">Mot de passe</label>
                    <input type="password" id="password" name="password" class="w-full p-2 border rounded" required>
                </div>
                
                <div>
                    <label for="role" class="block text-gray-700">Type de compte</label>
                    <select name="role" id="role" class="w-full p-2 border rounded" required>
                        <option value="client">Client</option>
                        <option value="livreur">Livreur</option>
                        <option value="commercant">Commerçant</option>
                        <option value="prestataire">Prestataire</option>
                    </select>
                </div>
                
                <div id="identity-document-section" class="hidden">
                    <label for="identity_document" class="block text-gray-700">Document d'identité</label>
                    <p class="text-sm text-gray-500 mb-2">Veuillez fournir une pièce d'identité valide (carte d'identité, passeport, etc.)</p>
                    <input type="file" id="identity_document" name="identity_document" class="w-full p-2 border rounded">
                </div>
                
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">S'inscrire</button>
            </form>
            
            <div class="mt-4 text-center">
                <p class="text-gray-600">Déjà inscrit ? <a href="login.php" class="text-blue-600 hover:underline">Connectez-vous</a></p>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
    
    <script>
        document.getElementById('role').addEventListener('change', function() {
            const identitySection = document.getElementById('identity-document-section');
            if (this.value !== 'client') {
                identitySection.classList.remove('hidden');
            } else {
                identitySection.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
