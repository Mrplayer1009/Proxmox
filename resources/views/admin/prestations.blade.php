@extends('layouts.admin')
@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Toutes les réservations</h2>
    <table class="min-w-full bg-white rounded shadow mb-8">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Prestation</th>
                <th class="px-4 py-2">Client</th>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $reservation)
                <tr>
                    <td class="border px-4 py-2">{{ $reservation->id_reservation }}</td>
                    <td class="border px-4 py-2">{{ $reservation->prestation->nom ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $reservation->client->nom ?? '-' }} {{ $reservation->client->prenom ?? '' }}</td>
                    <td class="border px-4 py-2">{{ $reservation->date }}</td>
                    <td class="border px-4 py-2">{{ $reservation->statut }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h2 class="text-2xl font-bold mb-6">Prestataires</h2>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Nom</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prestataires as $prestataire)
                <tr>
                    <td class="border px-4 py-2">{{ $prestataire->id_prestataire }}</td>
                    <td class="border px-4 py-2">{{ $prestataire->utilisateur->nom ?? '-' }} {{ $prestataire->utilisateur->prenom ?? '' }}</td>
                    <td class="border px-4 py-2">{{ $prestataire->utilisateur->email ?? '-' }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('admin.prestataire.prestations', $prestataire->id_prestataire) }}" class="bg-blue-500 text-white px-4 py-2 rounded">Voir ses prestations</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 