<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = $user['is_admin'];
        header('Location: ../index.php');
    } else {
        echo "Identifiants incorrects";
    }
}
?>

<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col justify-center items-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Connexion</h2>
            <form method="post" class="space-y-4">
            <script src="https://cdn.tailwindcss.com"></script>
            <div>
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" class="w-full p-2 border rounded" placeholder="Placeholder" required>
                </div>
                <div>
                    <label class="block text-gray-700">Mot de passe</label>
                    <input type="password" name="password" class="w-full p-2 border rounded" placeholder="Placeholder" required>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"> Connexion</button>
</form>