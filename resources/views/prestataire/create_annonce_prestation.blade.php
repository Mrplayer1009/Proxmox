@extends('layouts.app')
@section('content')
@include('layouts.navbar')
<div class="container mx-auto py-8 max-w-lg">
    <h2 class="text-2xl font-bold mb-6">Créer une annonce prestation</h2>
    <form method="POST" action="{{ route('prestataire.annonce_prestation.store') }}">
        @csrf
        <div class="mb-4">
            <label for="titre" class="block mb-1 font-semibold">Titre</label>
            <input type="text" name="titre" id="titre" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block mb-1 font-semibold">Description</label>
            <textarea name="description" id="description" class="w-full border rounded px-3 py-2"></textarea>
        </div>
        <div class="mb-4">
            <label for="prix" class="block mb-1 font-semibold">Prix (€)</label>
            <input type="number" name="prix" id="prix" class="w-full border rounded px-3 py-2" min="0" step="0.01" required>
        </div>
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Publier l'annonce</button>
    </form>
</div>
@endsection 