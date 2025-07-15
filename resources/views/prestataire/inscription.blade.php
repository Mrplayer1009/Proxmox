@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Inscription Prestataire</h2>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('prestataire.inscription.store') }}">
        @csrf
        <div class="form-group">
            <label for="nom_entreprise">Nom de l'entreprise</label>
            <input type="text" name="nom_entreprise" id="nom_entreprise" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="siret">SIRET</label>
            <input type="text" name="siret" id="siret" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="adresse">Adresse</label>
            <input type="text" name="adresse" id="adresse" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="telephone">Téléphone</label>
            <input type="text" name="telephone" id="telephone" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">S'inscrire</button>
    </form>
</div>
@endsection 