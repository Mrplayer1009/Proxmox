@extends('layouts.app')
@include('layouts.admin')
@section('content')
<div class="container">
    <h2>Dashboard Admin</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <h3>Prestataires en attente de validation</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom entreprise</th>
                <th>SIRET</th>
                <th>Adresse</th>
                <th>Téléphone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prestataires as $prestataire)
                @if($prestataire->statut_validation == 'en_attente')
                <tr>
                    <td>{{ $prestataire->id_prestataire }}</td>
                    <td>{{ $prestataire->nom_entreprise }}</td>
                    <td>{{ $prestataire->siret }}</td>
                    <td>{{ $prestataire->adresse }}</td>
                    <td>{{ $prestataire->telephone }}</td>
                    <td>
                        <form action="{{ route('admin.prestataires.valider', $prestataire->id_prestataire) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Accepter</button>
                        </form>
                        <form action="{{ route('admin.prestataires.refuser', $prestataire->id_prestataire) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Refuser</button>
                        </form>
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    <h3>Prestataires validés</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom entreprise</th>
                <th>SIRET</th>
                <th>Adresse</th>
                <th>Téléphone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prestataires as $prestataire)
                @if($prestataire->statut_validation == 'validé')
                <tr>
                    <td>{{ $prestataire->id_prestataire }}</td>
                    <td>{{ $prestataire->nom_entreprise }}</td>
                    <td>{{ $prestataire->siret }}</td>
                    <td>{{ $prestataire->adresse }}</td>
                    <td>{{ $prestataire->telephone }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    <h3>Prestataires refusés</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom entreprise</th>
                <th>SIRET</th>
                <th>Adresse</th>
                <th>Téléphone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prestataires as $prestataire)
                @if($prestataire->statut_validation == 'refusé')
                <tr>
                    <td>{{ $prestataire->id_prestataire }}</td>
                    <td>{{ $prestataire->nom_entreprise }}</td>
                    <td>{{ $prestataire->siret }}</td>
                    <td>{{ $prestataire->adresse }}</td>
                    <td>{{ $prestataire->telephone }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@endsection 