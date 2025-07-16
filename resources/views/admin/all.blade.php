@extends('layouts.app')
@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin EcoDeli</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.prestataires') }}">Prestataires</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.livreurs.validation') }}">Livreurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.commercants') }}">Commerçants</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.batiments') }}">Bâtiments</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <h2>Administration - Toutes les données</h2>
    <h3>Utilisateurs</h3>
    <form method="GET" action="" class="mb-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Recherche par nom, id, prénom ou mail" class="form-control d-inline-block w-auto" style="width:300px;">
        <button type="submit" class="btn btn-primary">Rechercher</button>
    </form>
    <table style="width:100%;margin-bottom:2rem;">
        <tr>
            <th>ID</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Type</th><th>Statut</th>
        </tr>
        @foreach($utilisateurs as $u)
        <tr>
            <td>{{ $u->id_utilisateur }}</td>
            <td>{{ $u->nom }}</td>
            <td>{{ $u->prenom }}</td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->type_utilisateur }}</td>
            <td>
                {{ $u->statut_compte }}
                <form action="{{ route('admin.utilisateur.statut', $u->id_utilisateur) }}" method="POST" style="display:inline-block;margin-left:10px;">
                    @csrf
                    <select name="statut_compte" onchange="this.form.submit()">
                        <option value="actif" {{ $u->statut_compte == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ $u->statut_compte == 'inactif' ? 'selected' : '' }}>Inactif</option>
                        <option value="suspendu" {{ $u->statut_compte == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                    </select>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    <h3>Annonces</h3>
    <table style="width:100%;margin-bottom:2rem;">
        <tr>
            <th>ID</th><th>Utilisateur</th><th>Description</th><th>Ville</th><th>Date</th><th>Statut</th>
        </tr>
        @foreach($annonces as $a)
        <tr>
            <td>{{ $a->id_annonce }}</td>
            <td>{{ $a->id_utilisateur }}</td>
            <td>{{ $a->description }}</td>
            <td>{{ $a->lieu_depart }}</td>
            <td>{{ $a->date_souhaitee }}</td>
            <td>{{ $a->statut }}</td>
        </tr>
        @endforeach
    </table>
    <h3>Paiements</h3>
    <table style="width:100%;margin-bottom:2rem;">
        <tr>
            <th>ID</th><th>Utilisateur</th><th>Montant</th><th>Date</th><th>Méthode</th><th>Statut</th>
        </tr>
        @foreach($paiements as $p)
        <tr>
            <td>{{ $p->id_paiement }}</td>
            <td>{{ $p->id_utilisateur }}</td>
            <td>{{ $p->montant }}</td>
            <td>{{ $p->date }}</td>
            <td>{{ $p->methode }}</td>
            <td>{{ $p->statut }}</td>
        </tr>
        @endforeach
    </table>
    <h3>Livraisons</h3>
    <table style="width:100%;margin-bottom:2rem;">
        <tr>
            <th>ID</th><th>Annonce</th><th>Livreur</th><th>Date livraison</th><th>Code</th><th>Statut</th>
        </tr>
        @foreach($livraisons as $l)
        <tr>
            <td>{{ $l->id_livraison }}</td>
            <td>{{ $l->id_annonce }}</td>
            <td>{{ $l->id_livreur }}</td>
            <td>{{ $l->date_livraison }}</td>
            <td>{{ $l->code_validation }}</td>
            <td>{{ $l->statut }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endsection 