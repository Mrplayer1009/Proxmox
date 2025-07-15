@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Mes livraisons</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">#</th>
                    <th class="py-2 px-4 border-b">Date livraison</th>
                    <th class="py-2 px-4 border-b">Code validation</th>
                    <th class="py-2 px-4 border-b">Poids</th>
                    <th class="py-2 px-4 border-b">Fragile</th>
                    <th class="py-2 px-4 border-b">Statut</th>
                    <th class="py-2 px-4 border-b">Contenu</th>
                    <th class="py-2 px-4 border-b">Modalité</th>
                    <th class="py-2 px-4 border-b">Lieu actuel</th>
                    <th class="py-2 px-4 border-b">Lieu arrivée</th>
                </tr>
            </thead>
            <tbody>
                @forelse($livraisons as $livraison)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $livraison->id_livraison }}</td>
                        <td class="py-2 px-4 border-b">{{ $livraison->date_livraison }}</td>
                        <td class="py-2 px-4 border-b">{{ $livraison->code_validation }}</td>
                        <td class="py-2 px-4 border-b">{{ $livraison->poids }}</td>
                        <td class="py-2 px-4 border-b">{{ $livraison->fragile ? 'Oui' : 'Non' }}</td>
                        <td class="py-2 px-4 border-b">{{ ucfirst($livraison->statut) }}</td>
                        <td class="py-2 px-4 border-b">{{ $livraison->contenu }}</td>
                        <td class="py-2 px-4 border-b">{{ $livraison->modalite }}</td>
                        <td class="py-2 px-4 border-b">
                            @if(isset($livraison->adresseDepart))
                                {{ $livraison->adresseDepart->ville ?? '' }}, {{ $livraison->adresseDepart->rue ?? '' }}
                            @else
                                {{ $livraison->id_adresse_depart }}
                            @endif
                        </td>
                        <td class="py-2 px-4 border-b">
                            @if(isset($livraison->adresseArrivee))
                                {{ $livraison->adresseArrivee->ville ?? '' }}, {{ $livraison->adresseArrivee->rue ?? '' }}
                            @else
                                {{ $livraison->id_adresse_arrivee }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="16" class="py-4 text-center text-gray-500">Aucune livraison trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection 