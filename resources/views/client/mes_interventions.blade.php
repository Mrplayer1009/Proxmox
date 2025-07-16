@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Mes interventions réservées</h2>
    @if($reservations->isEmpty())
        <p>Aucune intervention réservée.</p>
    @else
        <table class="min-w-full bg-white border border-gray-200 mb-6">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Titre</th>
                    <th class="py-2 px-4 border-b">Date</th>
                    <th class="py-2 px-4 border-b">Heure début</th>
                    <th class="py-2 px-4 border-b">Heure fin</th>
                    <th class="py-2 px-4 border-b">Statut</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $reservation)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $reservation->prestation->nom ?? $reservation->id_prestation }}</td>
                        <td class="py-2 px-4 border-b">{{ $reservation->date }}</td>
                        <td class="py-2 px-4 border-b">{{ $reservation->heure_debut }}</td>
                        <td class="py-2 px-4 border-b">{{ $reservation->heure_fin }}</td>
                        <td class="py-2 px-4 border-b">{{ ucfirst($reservation->statut) }}</td>
                        <td class="py-2 px-4 border-b">
                            @if($reservation->statut === 'en_attente')
                                <form action="{{ route('client.reservation.annuler', $reservation->id_reservation) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">Annuler</button>
                                </form>
                                <form action="{{ route('client.reservation.valider', $reservation->id_reservation) }}" method="POST" class="inline ml-2">
                                    @csrf
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-3 rounded">Valider</button>
                                </form>
                            @elseif($reservation->statut === 'validée')
                                @if($reservation->note)
                                    <span class="text-green-700 font-semibold">Merci de votre retour</span>
                                @else
                                    <a href="{{ route('client.reservation.noter', $reservation->id_reservation) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded">Noter</a>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection 