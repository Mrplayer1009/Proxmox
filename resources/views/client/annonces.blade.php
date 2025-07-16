@extends('layouts.app')
@section('content')
@include('layouts.navbar')
<div class="container">
    <h2>Mes annonces</h2>
    <a href="{{ route('client.annonces.create') }}" class="button">Créer une annonce</a>
    @if(session('success'))<div class="success">{{ session('success') }}</div>@endif
    <table style="width:100%;margin-top:2rem;">
        <tr>
            <th>ID</th><th>Titre</th><th>Description</th><th>Départ</th><th>Date</th><th>Nombre</th><th>Statut</th><th>Actions</th>
        </tr>
        @foreach($annonces as $annonce)
        <tr>
            <td>{{ $annonce->id_annonce }}</td>
            <td>{{ $annonce->titre }}</td>
            <td>{{ $annonce->description }}</td>
            <td>{{ $annonce->lieu_depart ?? $annonce->ville_depart }}</td>
            <td>{{ $annonce->date_souhaitee ?? $annonce->date_limite }}</td>
            <td>{{ $annonce->nombre }}</td>
            <td>{{ $annonce->statut }}</td>
            <td style="display:flex;gap:0.5rem;align-items:center;">
                <form method="POST" action="{{ route('client.annonces.destroy', $annonce->id_annonce) }}" onsubmit="return confirm('Supprimer cette annonce ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="color:red;">Supprimer</button>
                </form>
                <form method="POST" action="{{ route('client.annonces.changer_stock', $annonce->id_annonce) }}" style="display:inline-flex;align-items:center;gap:0.2rem;">
                    @csrf
                    <input type="number" name="nombre" value="{{ $annonce->nombre }}" min="0" style="width:60px;">
                    <button type="submit">Changer stock</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection 