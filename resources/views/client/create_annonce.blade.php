@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Créer une annonce</h2>
    <form method="POST" action="{{ route('client.annonces.store') }}">
        @csrf
        <div>
            <label for="titre">Titre</label>
            <input type="text" name="titre" id="titre" value="{{ old('titre') }}" required>
        </div>
        <div>
            <label for="ville_depart">Ville</label>
            <input type="text" name="ville_depart" id="ville_depart" value="{{ old('ville_depart') }}" required>
        </div>
        <div>
            <label for="poids">Poids (kg)</label>
            <input type="number" name="poids" id="poids" value="{{ old('poids', 0) }}" min="0" step="0.01" required>
        </div>
        <div id="fragile-container" style="display: none;">
            <label for="fragile">
                <input type="checkbox" name="fragile" id="fragile" value="1" {{ old('fragile') ? 'checked' : '' }}>
                Fragile
            </label>
        </div>
        <div>
            <label for="description">Description</label>
            <textarea name="description" id="description" required>{{ old('description') }}</textarea>
        </div>
        <div>
            <label for="prix_propose">Prix proposé (€)</label>
            <input type="number" name="prix" id="prix" value="{{ old('prix') }}" min="0" step="0.01" required>
        </div>
        <div>
            <label for="date_limite">Date limite</label>
            <input type="date" name="date_limite" id="date_limite" value="{{ old('date_limite') }}">
        </div>
        <div>
            <label for="type_colis">Type de colis</label>
            <select name="type_colis" id="type_colis" required>
                <option value="">-- Sélectionner --</option>
                <option value="Alimentaire" {{ old('type_colis') == 'Alimentaire' ? 'selected' : '' }}>Alimentaire</option>
                <option value="Meuble" {{ old('type_colis') == 'Meuble' ? 'selected' : '' }}>Meuble</option>
                <option value="Colis" {{ old('type_colis') == 'Colis' ? 'selected' : '' }}>Colis</option>
            </select>
        </div>
        <div>
            <label for="nombre">Nombre</label>
            <input type="number" name="nombre" id="nombre" value="{{ old('nombre', 1) }}" min="1" required>
        </div>
        <button type="submit">Créer</button>
    </form>
</div>
<script>
    function toggleFragile() {
        const poids = parseFloat(document.getElementById('poids').value);
        document.getElementById('fragile-container').style.display = (poids > 0) ? 'block' : 'none';
    }
    document.getElementById('poids').addEventListener('input', toggleFragile);
    window.addEventListener('DOMContentLoaded', toggleFragile);
</script>
@endsection 