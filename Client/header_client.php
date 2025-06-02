<header class="bg-white shadow-md">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center">
                <a href="dashboard.php" class="text-xl font-bold text-green-600">EcoDeli</a>
            </div>
            <nav class="hidden md:flex space-x-4">
                <a href="dashboard.php" class="text-gray-600 hover:text-green-600">Tableau de bord</a>
                <a href="deposer_annonce.php" class="text-gray-600 hover:text-green-600">Déposer une annonce</a>
                <a href="services.php" class="text-gray-600 hover:text-green-600">Services</a>
                <a href="rendez_vous.php" class="text-gray-600 hover:text-green-600">Rendez-vous</a>
                <a href="paiements.php" class="text-gray-600 hover:text-green-600">Paiements</a>
                <a href="box_stockage.php" class="text-gray-600 hover:text-green-600">Box de stockage</a>
            </nav>
            <div class="flex items-center">
                <a href="../php/profil.php" class="text-gray-600 hover:text-green-600 mr-4">Mon profil</a>
                <a href="../php/deconnexion.php" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">Déconnexion</a>
            </div>
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-600 hover:text-green-600 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div id="mobile-menu" class="md:hidden hidden py-2">
            <a href="dashboard.php" class="block py-2 text-gray-600 hover:text-green-600">Tableau de bord</a>
            <a href="deposer_annonce.php" class="block py-2 text-gray-600 hover:text-green-600">Déposer une annonce</a>
            <a href="services.php" class="block py-2 text-gray-600 hover:text-green-600">Services</a>
            <a href="rendez_vous.php" class="block py-2 text-gray-600 hover:text-green-600">Rendez-vous</a>
            <a href="paiements.php" class="block py-2 text-gray-600 hover:text-green-600">Paiements</a>
            <a href="box_stockage.php" class="block py-2 text-gray-600 hover:text-green-600">Box de stockage</a>
        </div>
    </div>
</header>

<script>
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });
</script>
