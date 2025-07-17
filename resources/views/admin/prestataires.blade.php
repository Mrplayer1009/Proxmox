@extends('layouts.admin')
@section('content')
<div class="container">
    <h2>Gestion des prestataires</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom entreprise</th>
                <th>SIRET</th>
                <th>Adresse</th>
                <th>Téléphone</th>
                <th>Statut</th>
                <th>Pièce justificative</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prestataires as $prestataire)
            <tr>
                <td>{{ $prestataire->id_prestataire }}</td>
                <td>{{ $prestataire->nom_entreprise }}</td>
                <td>{{ $prestataire->siret }}</td>
                <td>{{ $prestataire->adresse }}</td>
                <td>{{ $prestataire->telephone }}</td>
                <td>
                    @if($prestataire->statut_validation == 'validé')
                        <span class="text-success">Validé</span>
                    @elseif($prestataire->statut_validation == 'refusé')
                        <span class="text-danger">Refusé</span>
                    @else
                        <span class="text-warning">En attente</span>
                    @endif
                </td>
                <td>
                    @if($prestataire->piece_justificative)
                        <a href="{{ asset('storage/' . $prestataire->piece_justificative) }}" target="_blank" class="btn btn-outline-info btn-sm">Voir</a>
                    @else
                        <span class="text-muted">Aucune</span>
                    @endif
                </td>
                <td>
                    @if($prestataire->statut_validation == 'en_attente')
                        <form action="{{ route('admin.prestataires.valider', $prestataire->id_prestataire) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Valider</button>
                        </form>
                        <form action="{{ route('admin.prestataires.refuser', $prestataire->id_prestataire) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Refuser</button>
                        </form>
                    @elseif($prestataire->statut_validation == 'validé')
                        <span class="text-success">Agréé</span>
                    @elseif($prestataire->statut_validation == 'refusé')
                        <span class="text-danger">Refusé</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 