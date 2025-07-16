@extends('layouts.app')

@section('content')
@include('layouts.navbar')
<div class="container mx-auto p-4">
    <div class="flex justify-end mb-4">
        <a href="{{ route('livreur.deliveries.prendre_liste') }}" class="bg-orange-500 text-black px-4 py-2 rounded hover:bg-orange-600 transition">Prendre une livraison en attente</a>
    </div>
    <h2 class="text-2xl font-bold mb-4">Mes Livraisons</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($deliveries->isEmpty())
        <p>Aucune livraison assignée.</p>
    @else
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">ID Livraison</th>
                    <th class="py-2 px-4 border-b">ID Annonce</th>
                    <th class="py-2 px-4 border-b">ID Livreur</th>
                    <th class="py-2 px-4 border-b">Date Livraison</th>
                    <th class="py-2 px-4 border-b">Code Validation</th>
                    <th class="py-2 px-4 border-b">Fragile</th>
                    <th class="py-2 px-4 border-b">Statut</th>
                    <th class="py-2 px-4 border-b">Contenu</th>
                    <th class="py-2 px-4 border-b">Date</th>
                    <th class="py-2 px-4 border-b">Modalité</th>
                    <th class="py-2 px-4 border-b">Type</th>
                    <th class="py-2 px-4 border-b">Lieu actuel</th>
                    <th class="py-2 px-4 border-b">Lieu arrivée</th>
                    <th class="py-2 px-4 border-b">MAJ le</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deliveries as $delivery)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $delivery->id_livraison }}</td>
                    <td class="py-2 px-4 border-b">{{ $delivery->id_annonce }}</td>
                    <td class="py-2 px-4 border-b">{{ $delivery->id_livreur }}</td>
                    <td class="py-2 px-4 border-b">{{ $delivery->date_livraison }}</td>
                    <td class="py-2 px-4 border-b">{{ $delivery->code_validation }}</td>
                    <td class="py-2 px-4 border-b">{{ $delivery->fragile ? 'Oui' : 'Non' }}</td>
                    <td class="py-2 px-4 border-b">{{ $delivery->statut }}</td>
                    <td class="py-2 px-4 border-b">{{ $delivery->contenu }}</td>
                    <td class="py-2 px-4 border-b">{{ $delivery->date }}</td>
                    <td class="py-2 px-4 border-b">{{ $delivery->modalite }}</td>
                    <td class="py-2 px-4 border-b">{{ $delivery->type }}</td>
                    <td class="py-2 px-4 border-b">
                        @if(isset($delivery->adresseDepart))
                            {{ $delivery->adresseDepart->rue ?? '' }}, {{ $delivery->adresseDepart->ville ?? '' }}
                        @else
                            {{ $delivery->id_adresse_depart }}
                        @endif
                    </td>
                    <td class="py-2 px-4 border-b">
                        {{ $delivery->adresseArrivee->rue ?? '' }}, {{ $delivery->adresseArrivee->ville ?? '' }}
                    </td>
                    <td class="py-2 px-4 border-b">{{ $delivery->updated_at }}</td>
                    <td class="py-2 px-4 border-b">
                        <a href="{{ route('livreur.deliveries.show', $delivery->id_livraison) }}" class="text-blue-600 hover:underline">Voir détails</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
