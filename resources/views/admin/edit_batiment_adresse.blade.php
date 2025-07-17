@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Modifier l'adresse du bâtiment : {{ $batiment->nom }}</h2>
    <form method="POST" action="{{ route('admin.batiment.update_adresse', $batiment->id) }}">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-1">Rue</label>
            <input type="text" name="rue" value="{{ old('rue', $batiment->addresse->rue ?? '') }}" class="w-full border rounded px-3 py-2" required>
            @error('rue')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Ville</label>
            <input type="text" name="ville" value="{{ old('ville', $batiment->addresse->ville ?? '') }}" class="w-full border rounded px-3 py-2" required>
            @error('ville')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Code postal</label>
            <input type="text" name="code_postal" value="{{ old('code_postal', $batiment->addresse->code_postal ?? '') }}" class="w-full border rounded px-3 py-2" required>
            @error('code_postal')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded transition">Enregistrer</button>
        <a href="{{ route('admin.batiments') }}" class="ml-4 text-gray-600 hover:underline">Annuler</a>
    </form>
</div>
@endsection 