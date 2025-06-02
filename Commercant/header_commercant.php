<header class="bg-white shadow-md">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <!-- Logo et nom -->
        <div class="flex items-center space-x-2">
            <a href="../index.php">
                <img src="../src/logo.png" alt="EcoDeli" class="h-10 w-auto">
            </a>
            <span class="text-xl font-bold text-yellow-600">EcoDeli Commerçant</span>
        </div>

        <!-- Menu de navigation -->
        <nav class="hidden md:flex items-center space-x-6">
            <a href="dashboard.php" class="text-gray-700 hover:text-yellow-600">
                <i class="fas fa-tachometer-alt mr-1"></i> Tableau de bord
            </a>
            <a href="contrat.php" class="text-gray-700 hover:text-yellow-600">
                <i class="fas fa-file-contract mr-1"></i> Contrat
            </a>
            <a href="annonces.php" class="text-gray-700 hover:text-yellow-600">
                <i class="fas fa-bullhorn mr-1"></i> Annonces
            </a>
            <a href="factures.php" class="text-gray-700 hover:text-yellow-600">
                <i class="fas fa-file-invoice mr-1"></i> Factures
            </a>
            <a href="paiements.php" class="text-gray-700 hover:text-yellow-600">
                <i class="fas fa-money-bill-wave mr-1"></i> Paiements
            </a>
            <a href="stockage.php" class="text-gray-700 hover:text-yellow-600">
                <i class="fas fa-warehouse mr-1"></i> Stockage
            </a>
        </nav>

        <!-- Menu utilisateur -->
        <div class="flex items-center space-x-4">
            <div class="relative group">
                <button class="flex items-center space-x-1 text-gray-700 hover:text-yellow-600">
                    <i class="fas fa-user-circle text-xl"></i>
                    <span class="hidden md:inline"><?= $_SESSION['prenom'] ?? 'Utilisateur' ?></span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
                <!-- Menu déroulant -->
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
                    <a href="profil.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2"></i> Mon profil
                    </a>
                    <a href="parametres.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog mr-2"></i> Paramètres
                    </a>
                    <div class="border-t border-gray-100"></div>
                    <a href="../php/deconnexion.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                    </a>
                </div>
            </div>
            
            <!-- Menu mobile -->
            <button class="md:hidden text-gray-700 hover:text-yellow-600" id="mobile-menu-button">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </div>
    
    <!-- Menu mobile (caché par défaut) -->
    <div class="md:hidden hidden bg-white border-t" id="mobile-menu">
        <div class="container mx-auto px-4 py-2">
            <a href="dashboard.php" class="block py-2 text-gray-700 hover:text-yellow-600">
                <i class="fas fa-tachometer-alt mr-1"></i> Tableau de bord
            </a>
            <a href="contrat.php" class="block py-2 text-gray-700 hover:text-yellow-600">
                <i class="fas fa-file-contract mr-1"></i> Contrat
            </a>
            <a href="annonces.php" class="block py-2 text-gray-700 hover:text-yellow-600">
                <i class="fas fa-bullhorn mr-1"></i> Annonces
            </a>
            <a href="factures.php" class="block py-2 text-gray-700 hover:text-yellow-600">
                <i class="fas fa-file-invoice mr-1"></i> Factures
            </a>
            <a href="paiements.php" class="block py-2 text-gray-700 hover:text-yellow-600">
                <i class="fas fa-money-bill-wave mr-1"></i> Paiements
            </a>
            <a href="stockage.php" class="block py-2 text-gray-700 hover:text-yellow-600">
                <i class="fas fa-warehouse mr-1"></i> Stockage
            </a>
        </div>
    </div>
</header>

<script>
    // Toggle mobile menu
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>
