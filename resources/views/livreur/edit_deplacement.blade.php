@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8 max-w-md">
    <h2 class="text-2xl font-bold mb-6">Modifier le déplacement</h2>
    <form method="POST" action="{{ route('livreur.deplacement.update', $deplacement->id_planning) }}" class="bg-white p-6 rounded shadow">
        @csrf
        <div class="mb-4">
            <label for="date" class="block font-semibold mb-2">Date du déplacement</label>
            <input type="date" name="date" id="date" class="w-full border rounded px-3 py-2" value="{{ $deplacement->date }}" required>
        </div>
        <div class="mb-4">
            <label for="lieu_arrivee" class="block font-semibold mb-2">Destination</label>
            <select name="lieu_arrivee" id="lieu_arrivee" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Choisir une destination --</option>
                @foreach($batiments as $batiment)
                    <option value="{{ $batiment->addresse->id }}" @if($batiment->addresse->id == $deplacement->lieu_arrivee) selected @endif>
                        {{ $batiment->nom }} - {{ $batiment->addresse->rue }}, {{ $batiment->addresse->ville }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="description" class="block font-semibold mb-2">Description (optionnel)</label>
            <input type="text" name="description" id="description" class="w-full border rounded px-3 py-2" value="{{ $deplacement->description }}">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full font-bold">Enregistrer les modifications</button>
    </form>
</div>
@endsection 