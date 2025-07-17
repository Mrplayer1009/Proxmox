@extends('layouts.app')
@section('content')
@include('layouts.navbar')
<div class="max-w-2xl mx-auto mt-10 bg-white rounded-xl shadow p-8">
    <h2 class="text-2xl font-bold text-orange-600 mb-6">Mon profil</h2>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('client.profil.update') }}">
        @csrf
        @method('POST')
        <div class="mb-4">
            <label class="block font-semibold mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded px-3 py-2" required>
            @error('email')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Téléphone</label>
            <input type="text" name="telephone" value="{{ old('telephone', $user->telephone) }}" class="w-full border rounded px-3 py-2">
            @error('telephone')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Nouveau mot de passe</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2">
            @error('password')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Rue</label>
            <input type="text" name="rue" value="{{ old('rue', $adresse->rue ?? '') }}" class="w-full border rounded px-3 py-2" required>
            @error('rue')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Ville</label>
            <input type="text" name="ville" value="{{ old('ville', $adresse->ville ?? '') }}" class="w-full border rounded px-3 py-2" required>
            @error('ville')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Code postal</label>
            <input type="text" name="code_postal" value="{{ old('code_postal', $adresse->code_postal ?? '') }}" class="w-full border rounded px-3 py-2" required>
            @error('code_postal')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded transition">Mettre à jour</button>
    </form>
</div>
@endsection 