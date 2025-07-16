@extends('layouts.app')
@section('content')
<div class="container max-w-lg mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Enregistrer votre entreprise</h2>
    <form action="{{ route('commercant.entreprise.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="nom" class="block font-semibold mb-1">Nom de l'entreprise</label>
            <input type="text" name="nom" id="nom" class="form-control w-full" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block font-semibold mb-1">Email</label>
            <input type="email" name="email" id="email" class="form-control w-full" value="{{ old('email', Auth::user()->email) }}" required>
        </div>
        <div class="mb-4">
            <label for="telephone" class="block font-semibold mb-1">Téléphone</label>
            <input type="text" name="telephone" id="telephone" class="form-control w-full">
        </div>
        <input type="hidden" name="id_addresse" value="{{ Auth::user()->id_addresse ?? '' }}">
        <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded hover:bg-orange-700 transition">Enregistrer</button>
    </form>
</div>
@endsection 