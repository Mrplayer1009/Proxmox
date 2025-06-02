<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoDeli</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/lucide-icons@0.263.0/dist/web/style.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <!-- Logo et nom -->
            <div class="flex items-center space-x-2">
            <a href="../index.php" class="block px-4 py-2 hover:bg-gray-100 text-sm text-red-600">
                <img src="../src/logo.png" alt="EcoDeli" class="h-14 w-auto">
            </a>
                <span class="text-xl font-bold text-blue-600">EcoDeli</span>
            </div>

            <!-- Menu de navigation central -->
            <nav class="flex items-center space-x-4">
                <div class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 cursor-pointer">
                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                    <span class="text-sm">Localisation</span>
                </div>
            </nav>

            <!-- Menus de droite -->
            <div class="flex items-center space-x-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Menus pour utilisateur connecté -->
                    <div class="flex items-center space-x-4">
    <a href="profil.php" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600">
        <i data-lucide="user" class="w-5 h-5"></i>
        <span class="text-sm">Profil</span>
    </a>
    <a href="deconnexion.php" class="flex items-center space-x-2 text-red-600 hover:text-red-800">
        <i data-lucide="log-out" class="w-5 h-5"></i>
        <span class="text-sm">Déconnexion</span>
    </a>
</div>


                    <div class="relative group">
                        <div class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 cursor-pointer">
                            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                            <span class="text-sm">Mon Panier</span>
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                        <!-- Menu déroulant Mon Panier -->
                        <div class="absolute hidden group-hover:block bg-white shadow-lg rounded-md mt-2 right-0 w-64 z-20 border">
                            <div class="p-4 text-center text-gray-500">
                                Votre panier est vide
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Menus pour utilisateur non connecté -->
                    <a href="login.php" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        <span class="text-sm">Connexion</span>
                    </a>
                    <a href="register.php" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600">
                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                        <span class="text-sm">Inscription</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
