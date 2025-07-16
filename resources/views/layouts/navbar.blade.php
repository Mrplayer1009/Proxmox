<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil EcoDeli</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white shadow mb-8">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <a href="{{ route('base') }}" class="flex items-center gap-2">
                    <img src="https://cdn-icons-png.flaticon.com/512/2909/2909765.png" alt="Logo" class="w-10 h-10">
                    <span class="font-bold text-xl text-orange-600">EcoDeli</span>
                </a>
            </div>
            <div class="flex gap-4">
                @if($user)
                    @if($user->type_utilisateur === 'client')
                        <a href="{{ route('client.dashboard') }}" class="text-gray-700 hover:text-orange-600 font-semibold">Espace Client</a>
                        <a href="{{ route('client.annonces') }}" class="text-gray-700 hover:text-orange-600">Mes Annonces</a>
                        <li><a href="{{ route('panier.afficher') }}" class="text-gray-700 hover:text-orange-600 font-semibold">Mon panier</a></li>
                        <li><a href="{{ route('annonces.index') }}" class="block px-4 py-2 hover:bg-gray-100">Annonces</a></li>
                        <li><a href="{{ route('annonces.prestations') }}" class="block px-4 py-2 hover:bg-gray-100">Annonces prestation</a></li>
                    @elseif($user->type_utilisateur === 'livreur')
                        <a href="{{ route('livreur.dashboard') }}" class="text-gray-700 hover:text-orange-600 font-semibold">Espace Livreur</a>
                        <a href="{{ route('client.dashboard') }}" class="text-gray-700 hover:text-orange-600 font-semibold">Espace Utilisateur</a>
                    @elseif($user->type_utilisateur === 'commercant')
                        <a href="{{ route('commercant.dashboard') }}" class="text-gray-700 hover:text-orange-600 font-semibold">Espace Commerçant</a>
                        <a href="{{ route('client.dashboard') }}" class="text-gray-700 hover:text-orange-600 font-semibold">Espace Utilisateur</a>

                    @elseif($user->type_utilisateur === 'prestataire')
                        <a href="{{ route('prestataire.dashboard') }}" class="text-gray-700 hover:text-orange-600 font-semibold">Espace Prestataire</a>
                        <a href="{{ route('client.dashboard') }}" class="text-gray-700 hover:text-orange-600 font-semibold">Espace Utilisateur</a>

                    @elseif($user->type_utilisateur === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-orange-600 font-semibold">Back Office Admin</a>
                        <a href="{{ route('client.dashboard') }}" class="text-gray-700 hover:text-orange-600 font-semibold">Espace Utilisateur</a>
                        <a href="{{ route('prestataire.dashboard') }}" class="text-gray-700 hover:text-orange-600 font-semibold">Espace Prestataire</a>
                        <a href="{{ route('livreur.dashboard') }}" class="text-gray-700 hover:text-orange-600 font-semibold">Espace Livreur</a>
                        <a href="{{ route('commercant.dashboard') }}" class="text-gray-700 hover:text-orange-600 font-semibold">Espace Commerçant</a>




                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="ml-4 text-red-600 hover:underline">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-orange-600">Connexion</a>
                    <a href="{{ route('register') }}" class="text-gray-700 hover:text-orange-600">Inscription</a>
                @endif
            </div>
        </div>
    </nav>