@extends('layouts.app')

@section('content')
<div class="container">
</div>
@endsection

@php $user = Auth::user(); @endphp
<!DOCTYPE html>
<html lang="fr">
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
                <img src="https://cdn-icons-png.flaticon.com/512/2909/2909765.png" alt="Logo" class="w-10 h-10">
                <span class="font-bold text-xl text-orange-600">EcoDeli</span>
            </div>
            <div class="flex gap-4">
                @if($user)
                    @if($user->type_utilisateur === 'client')
                        <a href="{{ route('client.dashboard') }}" class="text-gray-700 hover:text-orange-600 font-semibold">Espace Client</a>
                        <a href="{{ route('client.annonces') }}" class="text-gray-700 hover:text-orange-600">Mes Annonces</a>
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
    </nav>
    <main class="max-w-2xl mx-auto bg-white rounded-xl shadow p-8 mt-8">
        <h1 class="text-3xl font-bold text-orange-600 mb-4">Bienvenue sur EcoDeli</h1>
        <p class="text-gray-700 mb-6">EcoDeli est la plateforme éco-responsable qui connecte clients, commerçants, livreurs et prestataires pour faciliter le transport de colis, la livraison à domicile, les services à la personne et bien plus encore.</p>
        <ul class="list-disc pl-6 text-gray-800 space-y-2">
            <li>Gestion des annonces de transport et de services</li>
            <li>Livraison de colis et suivi en temps réel</li>
            <li>Services à la personne, courses, transferts, garde d'animaux</li>
            <li>Back office d'administration pour une gestion complète</li>
        </ul>
        <div class="mt-8 text-center">
            <span class="text-gray-500">Connecté en tant que :</span>
            <span class="font-semibold text-orange-600">{{ $user->prenom ?? '' }} {{ $user->nom ?? '' }} (Invité)</span>
        </div>
        @if($user && $user->type_utilisateur === 'admin')
            <div class="mt-6 text-center">
                <a href="{{ route('admin.stripe') }}" class="inline-block bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700 transition">Paiements Stripe (Admin)</a>
            </div>
        @endif
        <div class="mt-8 text-center">
            <a href="{{ route('client.abonnement') }}" class="inline-block bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-orange-600 transition">Souhaitez-vous vous abonner&nbsp;?</a>
        </div>
        <div class="mt-8 text-center">
        <li><a href="{{ route('commerces.index') }}" class="text-gray-700 hover:text-orange-600 font-semibold">Commerces</a></li>
                <li><a href="{{ route('annonces.index') }}" class="text-gray-700 hover:text-orange-600 font-semibold">Annonces</a></li>
                <li><a href="{{ route('livraisons.index') }}" class="text-gray-700 hover:text-orange-600 font-semibold">Livraisons</a></li>
            </div>
    </main>
</body>
</html>
