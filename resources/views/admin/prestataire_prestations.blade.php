@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Prestataire : {{ $prestataire->utilisateur->nom ?? '' }} {{ $prestataire->utilisateur->prenom ?? '' }}</h2>
    <h3 class="text-xl font-semibold mb-4">Réservations</h3>
    <table class="min-w-full bg-white rounded shadow mb-8">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Client</th>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2">Statut</th>
                <th class="px-4 py-2">Note</th>
                <th class="px-4 py-2">Commentaire</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $reservation)
                <tr>
                    <td class="border px-4 py-2">{{ $reservation->id_reservation }}</td>
                    <td class="border px-4 py-2">{{ $reservation->client->nom ?? '-' }} {{ $reservation->client->prenom ?? '' }}</td>
                    <td class="border px-4 py-2">{{ $reservation->date }}</td>
                    <td class="border px-4 py-2">{{ $reservation->statut }}</td>
                    <td class="border px-4 py-2">{{ $reservation->note ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $reservation->commentaire ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h3 class="text-xl font-semibold mb-4">Notes et commentaires</h3>
    <ul class="mb-8">
        @forelse($notes as $note)
            <li>Note : <span class="font-bold">{{ $note }}</span></li>
        @empty
            <li>Aucune note.</li>
        @endforelse
        @forelse($commentaires as $commentaire)
            <li>Commentaire : <span class="italic">{{ $commentaire }}</span></li>
        @empty
        @endforelse
    </ul>
    <h3 class="text-xl font-semibold mb-4">Annonces prestation</h3>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Titre</th>
                <th class="px-4 py-2">Description</th>
                <th class="px-4 py-2">Prix</th>
                <th class="px-4 py-2">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($annonces_prestation as $annonce)
                <tr>
                    <td class="border px-4 py-2">{{ $annonce->id_annonce_prestation }}</td>
                    <td class="border px-4 py-2">{{ $annonce->titre }}</td>
                    <td class="border px-4 py-2">{{ $annonce->description }}</td>
                    <td class="border px-4 py-2">{{ $annonce->prix }}</td>
                    <td class="border px-4 py-2">{{ $annonce->statut }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.prestations') }}" class="mt-6 inline-block text-gray-600 hover:underline">&larr; Retour à la liste des prestations</a>
</div>
@endsection 