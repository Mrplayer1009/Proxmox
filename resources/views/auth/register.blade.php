@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Inscription</h2>
    <form method="POST" action="{{ url('/register') }}">
        @csrf
        <div>
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required>
            @error('nom')<div>{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" required>
            @error('prenom')<div>{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            @error('email')<div>{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
            @error('password')<div>{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="password_confirmation">Confirmation du mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
        </div>
        <div>
            <label for="telephone">Téléphone</label>
            <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}">
            @error('telephone')<div>{{ $message }}</div>@enderror
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
        <div>
            <label for="adresse">Adresse</label>
            <textarea name="adresse" id="adresse">{{ old('adresse') }}</textarea>
            @error('adresse')<div>{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="type_utilisateur">Type d'utilisateur</label>
            <select name="type_utilisateur" id="type_utilisateur" required>
                <option value="">Sélectionner</option>
                <option value="client" @if(old('type_utilisateur')=='client') selected @endif>Client</option>
                <option value="commercant" @if(old('type_utilisateur')=='commercant') selected @endif>Commerçant</option>
                <option value="livreur" @if(old('type_utilisateur')=='livreur') selected @endif>Livreur</option>
                <option value="prestataire" @if(old('type_utilisateur')=='prestataire') selected @endif>Prestataire</option>
                <option value="admin" @if(old('type_utilisateur')=='admin') selected @endif>Admin</option>
            </select>
            @error('type_utilisateur')<div>{{ $message }}</div>@enderror
        </div>
        <button type="submit">S'inscrire</button>
    </form>
</div>
@endsection 