<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <!-- Logo et nom -->
            <div class="flex items-center space-x-2">
            <a href="index.php" class="block px-4 py-2 hover:bg-gray-100 text-sm text-red-600">
                <img src="src/logo.png" alt="EcoDeli" class="h-14 w-auto">
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
    <a href="php/profil.php" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600">
        <i data-lucide="user" class="w-5 h-5"></i>
        <span class="text-sm">Profil</span>
    </a>
    <a href="php/deconnexion.php" class="flex items-center space-x-2 text-red-600 hover:text-red-800">
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
                    <a href="php/login.php" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        <span class="text-sm">Connexion</span>
                    </a>
                    <a href="php/register.php" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600">
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

<body class="bg-gray-50">

<a href="https://github.com/DariusESGI/PA2">
<button class="top-5 flex gap-3 cursor-pointer text-white font-semibold bg-gradient-to-r from-gray-800 to-black px-7 py-3 rounded-full border border-gray-600 hover:scale-105 duration-200 hover:text-gray-500 hover:border-gray-800 hover:from-black hover:to-gray-900">
  <svg viewBox="0 0 24 24" height="24" width="24" xmlns="http://www.w3.org/2000/svg"><path fill="#FFFFFF" d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"></path></svg>
  Github
</button>
</a>

<div class="flex flex-col items-center space-y-6 p-6">


    <!-- Devenir Livreur -->
    <div class="text-lg font-semibold">Menu <span class="font-bold">Livreur</span> ?</div>
    <a href="Livreur/dashboard.php">
    <button class="cursor-pointer transition-all 
bg-gray-700 text-white px-6 py-2 rounded-lg
border-green-400
border-b-[4px] hover:brightness-110 hover:-translate-y-[1px] hover:border-b-[6px]
active:border-b-[2px] active:brightness-90 active:translate-y-[2px] hover:shadow-xl hover:shadow-green-400 shadow-green-300 active:shadow-none">
  Rejoindre
</button>
    </a>

    <!-- Devenir Prestataire -->
    <div class="text-lg font-semibold">Menu <span class="font-bold">Prestataire</span> ?</div>
    <a href="Prestataire/dashboard.php">
    <button class="cursor-pointer transition-all 
bg-gray-700 text-white px-6 py-2 rounded-lg
border-blue-400
border-b-[4px] hover:brightness-110 hover:-translate-y-[1px] hover:border-b-[6px]
active:border-b-[2px] active:brightness-90 active:translate-y-[2px] hover:shadow-xl hover:shadow-blue-400 shadow-blue-300 active:shadow-none">
  Rejoindre
</button>
    </a>

    <!-- Devenir Commerçant -->
    <div class="text-lg font-semibold">Menu <span class="font-bold">Commerçant</span> ?</div>
    <a href="Commercant/dashboard.php">
    <button class="cursor-pointer transition-all 
bg-gray-700 text-white px-6 py-2 rounded-lg
border-red-400
border-b-[4px] hover:brightness-110 hover:-translate-y-[1px] hover:border-b-[6px]
active:border-b-[2px] active:brightness-90 active:translate-y-[2px] hover:shadow-xl hover:shadow-red-400 shadow-pink-300 active:shadow-none">
  Rejoindre
</button>
    </a>
</div>





</body>
    <footer class="bg-white border-t mt-8">
    <div class="container mx-auto py-6 px-4 flex flex-col md:flex-row justify-between items-center">
        <div class="flex items-center space-x-2">
            <a href="php/backoffice.php">
            <img src="src/logo.png" alt="EcoDeli" class="h-12">
            </a>
            <span class="text-gray-700">EcoDeli © 2025. Tous droits réservés.</span>
        </div>

        <div class="flex space-x-4">
            <input type="email" placeholder="Entrez votre mail pour les nouvelles" 
                   class="px-4 py-2 border rounded-md text-gray-700 focus:outline-none">
            <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Abonnez-vous
            </button>
        </div>

        <div class="flex space-x-4 mt-4 md:mt-0">
            <span class="font-semibold text-gray-700">Rejoignez nous</span>
            <a href="#" class="text-gray-500 hover:text-black"><i class="fab fa-youtube"></i></a>
            <a href="#" class="text-gray-500 hover:text-black"><i class="fab fa-facebook"></i></a>
            <a href="#" class="text-gray-500 hover:text-black"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-gray-500 hover:text-black"><i class="fab fa-instagram"></i></a>
            <a href="#" class="text-gray-500 hover:text-black"><i class="fab fa-linkedin"></i></a>
        </div>
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>

<script>
fetch("http://localhost/PA2/api/api.php?route=user")
  .then(response => response.json())
  .then(data => {
    if (data && data.user_id) {
      console.log("User ID :", data.user_id);
      localStorage.setItem("user_id", data.user_id);
    } else {
      console.warn("user_id non trouvé dans la réponse API :", data);
    }
  })
  .catch(error => {
    console.error("Erreur API :", error);
  });
</script>


</body>
</html>
