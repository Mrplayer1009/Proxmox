@extends('layouts.app')
@include('layouts.navbar')
@section('content')
<div class="container mx-auto p-6 bg-blue-100 rounded-lg shadow-md">
    <h2 class="text-3xl font-bold text-blue-800 mb-6">Tableau de bord Livreur</h2>
    <p class="mb-8 text-blue-700">Bienvenue sur votre espace livreur EcoDeli.</p>

    <nav class="space-y-4">
        <a href="{{ route('livreur.services') }}" class="block bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition">Voir les services EcoDeli</a>
        <a href="{{ route('livreur.deliveries') }}" class="block bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition">Gérer mes livraisons</a>
        <a href="{{ route('livreur.planning.index') }}" class="block bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition">Mon planning</a>
        <a href="{{ route('livreur.deplacements') }}" class="block bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition">Déplacements</a>
        <a href="{{ route('livreur.paiements') }}" class="block bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition">Mes paiements</a>
    </nav>
</div>
@endsection
