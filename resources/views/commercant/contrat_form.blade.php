@extends('layouts.app')
@section('content')
@include('layouts.navbar')
<div class="container max-w-lg mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Créer un contrat</h2>
    <form action="{{ route('commercant.contrat.store', $commercant->id_commercant) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="date_debut" class="block font-semibold mb-1">Date de début</label>
            <input type="date" name="date_debut" id="date_debut" class="form-control w-full" required>
        </div>
        <div class="mb-4">
            <label for="date_fin" class="block font-semibold mb-1">Date de fin</label>
            <input type="date" name="date_fin" id="date_fin" class="form-control w-full">
        </div>
        <div class="mb-4">
            <label for="fichier_pdf" class="block font-semibold mb-1">Fichier PDF du contrat (optionnel)</label>
            <input type="file" name="fichier_pdf" id="fichier_pdf" class="form-control w-full" accept="application/pdf">
        </div>
        <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded hover:bg-orange-700 transition">Créer le contrat</button>
    </form>
</div>
@endsection 