@extends('layouts.app')
@section('content')
@include('layouts.navbar')
@include('annonces.navbar')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Toutes les annonces prestation</h2>
    @if($annonces->isEmpty())
        <p>Aucune annonce prestation trouvée.</p>
    @else
        <table class="min-w-full bg-white border border-gray-200 mb-6">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Titre</th>
                    <th class="py-2 px-4 border-b">Description</th>
                    <th class="py-2 px-4 border-b">Prix</th>
                    <th class="py-2 px-4 border-b">Statut</th>
                    <th class="py-2 px-4 border-b">Prestataire</th>
                </tr>
            </thead>
            <tbody>
                @foreach($annonces as $annonce)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $annonce->titre }}</td>
                        <td class="py-2 px-4 border-b">{{ $annonce->description }}</td>
                        <td class="py-2 px-4 border-b">{{ number_format($annonce->prix, 2) }} €</td>
                        <td class="py-2 px-4 border-b">{{ ucfirst($annonce->statut) }}</td>
                        <td class="py-2 px-4 border-b">{{ $annonce->prestataire->nom_entreprise ?? $annonce->id_prestataire ?? '-' }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('annonces.prestations.prendre', $annonce->id_annonce_prestation) }}" class="bg-blue-600 hover:bg-blue-700 text-blue font-bold py-1 px-3 rounded">Prendre</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection 