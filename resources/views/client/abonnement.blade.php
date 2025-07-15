@extends('layouts.app')
@section('content')
<div class="container mx-auto max-w-2xl py-8">
    <h2 class="text-2xl font-bold mb-6">Mon abonnement</h2>
    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif
    @if($abonnement)
        <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
            <h3 class="font-semibold text-lg mb-2">Abonnement actuel : {{ $abonnement->nom }}</h3>
            <p><strong>Statut :</strong> {{ ucfirst($abonnement->statut) }}</p>
            <p><strong>Du :</strong> {{ $abonnement->date_debut }} au {{ $abonnement->date_fin }}</p>
            <p><strong>Prix :</strong> {{ number_format($abonnement->prix,2) }} €</p>
        </div>
    @endif
    <h3 class="font-semibold text-lg mb-4">Choisir un abonnement</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($catalogue as $key => $abo)
            <div class="border rounded-lg p-4 bg-white flex flex-col items-center">
                <h4 class="text-xl font-bold mb-2">{{ $abo['nom'] }}</h4>
                <p class="mb-2">{{ $abo['avantages'] }}</p>
                <div class="text-2xl font-extrabold text-orange-600 mb-2">{{ number_format($abo['prix'],2) }} €</div>
                @if($abonnement && strtolower($abonnement->nom) === strtolower($abo['nom']))
                    <span class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg font-semibold cursor-not-allowed">Déjà possédé</span>
                @else
                    <form action="{{ route('client.abonnement.paiement') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="{{ $key }}">
                        <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600 transition">Souscrire</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection 