@extends('layouts.app')
@section('content')
<div class="container mx-auto max-w-lg py-12">
    <div class="bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold text-blue-800 mb-4">Vérification d'identité requise</h2>
        @if(session('success'))<div class="mb-4 text-green-600">{{ session('success') }}</div>@endif
        @if($livreur && $livreur->statut_validation === 'en_attente')
            <p class="mb-4 text-blue-700">Votre pièce justificative a été envoyée. Veuillez attendre la validation par un administrateur.</p>
        @elseif($livreur && $livreur->statut_validation === 'refusé')
            <p class="mb-4 text-red-600">Votre pièce a été refusée. Merci de fournir un document valide.</p>
        @endif
        <form method="POST" action="{{ route('livreur.upload_piece') }}" enctype="multipart/form-data">
            @csrf
            <label for="piece" class="block mb-2">Pièce justificative (PDF, JPG, PNG, max 4Mo)</label>
            <input type="file" name="piece" id="piece" class="mb-4" required>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Envoyer</button>
        </form>
    </div>
</div>
@endsection 