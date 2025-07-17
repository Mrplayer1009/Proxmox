@extends('layouts.app')
@include('layouts.admin')
@section('content')
<div class="container mx-auto py-8 max-w-md">
    <h2 class="text-2xl font-bold mb-6">Enregistrer un bâtiment</h2>
    <form method="POST" action="{{ route('admin.batiment.store') }}" class="bg-white p-6 rounded shadow">
        @csrf
        <div class="mb-4">
            <label for="nom" class="block font-semibold mb-2">Nom du bâtiment</label>
            <input type="text" name="nom" id="nom" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label for="rue" class="block font-semibold mb-2">Rue</label>
            <input type="text" name="rue" id="rue" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label for="ville" class="block font-semibold mb-2">Ville</label>
            <input type="text" name="ville" id="ville" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label for="code_postal" class="block font-semibold mb-2">Code postal</label>
            <input type="text" name="code_postal" id="code_postal" class="w-full border rounded px-3 py-2" required>
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded w-full font-bold">Enregistrer le bâtiment</button>
    </form>
</div>
@endsection 