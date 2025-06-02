<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoDeli - Espace Livreur</title>
    <link href="../src/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-green-600 text-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center mb-4 md:mb-0">
                <a href="dashboard.php" class="text-2xl font-bold">EcoDeli</a>
                <span class="ml-2 bg-white text-green-600 px-2 py-1 rounded text-xs font-semibold">Espace Livreur</span>
            </div>
            
            <nav class="flex flex-wrap justify-center md:justify-end space-x-1 md:space-x-4">
                <a href="dashboard.php" class="px-3 py-2 rounded hover:bg-green-700 transition-colors">
                    <i class="fas fa-tachometer-alt mr-1"></i> Tableau de bord
                </a>
                <a href="annonces.php" class="px-3 py-2 rounded hover:bg-green-700 transition-colors">
                    <i class="fas fa-bullhorn mr-1"></i> Annonces
                </a>
                <a href="documents.php" class="px-3 py-2 rounded hover:bg-green-700 transition-colors">
                    <i class="fas fa-file-alt mr-1"></i> Documents
                </a>
                <a href="livraisons.php" class="px-3 py-2 rounded hover:bg-green-700 transition-colors">
                    <i class="fas fa-truck mr-1"></i> Livraisons
                </a>
                <a href="planning.php" class="px-3 py-2 rounded hover:bg-green-700 transition-colors">
                    <i class="fas fa-calendar-alt mr-1"></i> Planning
                </a>
                <a href="paiements.php" class="px-3 py-2 rounded hover:bg-green-700 transition-colors">
                    <i class="fas fa-money-bill-wave mr-1"></i> Paiements
                </a>
                <a href="../php/profil.php" class="px-3 py-2 rounded hover:bg-green-700 transition-colors">
                    <i class="fas fa-user mr-1"></i> Profil
                </a>
                <a href="../php/deconnexion.php" class="px-3 py-2 rounded bg-red-600 hover:bg-red-700 transition-colors">
                    <i class="fas fa-sign-out-alt mr-1"></i> Déconnexion
                </a>
            </nav>
        </div>
    </header>
    
    <main class="flex-grow">
