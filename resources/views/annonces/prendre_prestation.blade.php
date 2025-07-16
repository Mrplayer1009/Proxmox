@extends('layouts.app')
@section('content')
@include('annonces.navbar')
<div class="container mx-auto py-8 max-w-lg">
    <h2 class="text-2xl font-bold mb-6">Réserver la prestation : {{ $annonce->titre }}</h2>
    <form method="POST" action="{{ route('annonces.prestations.payer', $annonce->id_annonce_prestation) }}">
        @csrf
        <div class="mb-4">
            <label for="date" class="block mb-1 font-semibold">Date de la prestation</label>
            <input type="date" name="date" id="date" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label for="heures" class="block mb-1 font-semibold">Nombre d'heures</label>
            <input type="number" name="heures" id="heures" class="w-full border rounded px-3 py-2" min="1" step="1" required>
        </div>
        <div class="mb-4">
            <strong>Prix total : </strong>
            <span id="prix-total">{{ number_format($annonce->prix, 2) }} €</span>
        </div>
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Prendre rendez-vous</button>
    </form>
</div>
<script>
    const prixUnitaire = {{ $annonce->prix }};
    document.getElementById('heures').addEventListener('input', function() {
        const heures = parseInt(this.value) || 1;
        document.getElementById('prix-total').textContent = (prixUnitaire * heures).toFixed(2) + ' €';
    });
</script>
@endsection 