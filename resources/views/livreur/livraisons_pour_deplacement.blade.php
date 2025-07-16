@extends('layouts.app')
@section('content')
@include('layouts.navbar')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Livraisons pour le déplacement vers {{ $deplacement->lieu_arrivee }}</h2>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Direction</th>
                <th class="px-4 py-2">Statut</th>
                <th class="px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($livraisons as $livraison)
                <tr>
                    <td class="border px-4 py-2">{{ $livraison->id_livraison }}</td>
                    <td class="border px-4 py-2">
                        {{ $livraison->adresseArrivee->ville ?? '' }}
                    </td>
                    <td class="border px-4 py-2">{{ $livraison->statut }}</td>
                    <td class="border px-4 py-2">
                        @if(!$livraison->id_livreur)
                            <form action="{{ route('livreur.prendre_livraison', $livraison->id_livraison) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Prendre la livraison</button>
                            </form>
                        @else
                            @if($livraison->id_livreur == (auth()->user()->livreur->id_livreur ?? null))
                                <span class="text-gray-500">Déjà prise</span>
                            @else
                                <span class="text-red-500">Prise par un autre</span>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 