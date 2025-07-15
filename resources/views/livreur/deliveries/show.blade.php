@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Détails de la Livraison #{{ $delivery->id_livraison }}</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <strong>Lieu actuel :</strong>
        @if($delivery->localisations->count())
            {{ $delivery->localisations->sortByDesc('ordre')->first()->nom }}
        @else
            -
        @endif
    </div>
    <div class="mb-4">
        <strong>Lieu d'arrivée :</strong>
        {{ $delivery->adresseArrivee->rue ?? '' }}, {{ $delivery->adresseArrivee->ville ?? '' }}
    </div>

    <form action="{{ route('livreur.deliveries.locations.update', $delivery->id_livraison) }}" method="POST" class="space-y-4">
        @csrf

  

        <div>
            <a href="{{ route('livreur.deliveries') }}" class="ml-4 text-gray-600 hover:underline">Retour aux livraisons</a>
        </div>
    </form>
    @if($delivery->localisations->count())
        <div class="mt-6">
            <h3 class="font-semibold mb-2">Destinations enregistrées :</h3>
            <ul class="list-disc list-inside">
                @foreach($delivery->localisations->sortBy('ordre') as $localisation)
                    <li>{{ $localisation->nom }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($delivery->statut !== 'livrée')
        <form action="{{ route('livreur.deliveries.livree', $delivery->id_livraison) }}" method="POST" class="mb-4 flex items-center gap-2">
            @csrf
            <input type="text" name="code_validation" placeholder="Code de livraison" class="border rounded px-3 py-2" required>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Marquer comme livrée
            </button>
        </form>
    @endif

    <hr class="my-6">
    @if($delivery->statut !== 'livrée')
        <form action="{{ route('livreur.deliveries.add_location', $delivery->id_livraison) }}" method="POST" class="flex items-center gap-2 mb-4">
            @csrf
            <select name="lieu" class="border rounded px-3 py-2 w-full" required>
                <option value="">-- Choisir un bâtiment --</option>
                @foreach($batiments as $batiment)
                    <option value="{{ $batiment->nom }}">
                        {{ $batiment->nom }} - {{ $batiment->addresse->rue }}, {{ $batiment->addresse->ville }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ajouter</button>
        </form>
    @endif
</div>


<script>
    document.getElementById('add-location').addEventListener('click', function() {
        const locationsList = document.getElementById('locations-list');
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'locations[]';
        input.placeholder = 'Ajouter un lieu';
        input.className = 'w-full border border-gray-300 rounded px-3 py-2 mt-2';
        locationsList.appendChild(input);
    });
</script>

@endsection
