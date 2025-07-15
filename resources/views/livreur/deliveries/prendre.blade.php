@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Livraisons en attente</h2>
    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">#</th>
                    <th class="py-2 px-4 border-b">Entrepot</th>
                    <th class="py-2 px-4 border-b">Statut</th>
                    <th class="py-2 px-4 border-b">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($livraisons as $livraison)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $livraison->id_livraison }}</td>
                        <td class="py-2 px-4 border-b">
                            @if(isset($livraison->adresseDepart))
                                {{ $livraison->adresseDepart->rue ?? '' }}, {{ $livraison->adresseDepart->ville ?? '' }}
                                @if(isset($livraison->adresseDepart->batiment))
                                    <br><span class="text-gray-500 text-sm">{{ $livraison->adresseDepart->batiment->nom ?? '' }}</span>
                                @endif
                            @else
                                {{ $livraison->id_adresse_depart }}
                            @endif
                        </td>
                        <td class="py-2 px-4 border-b">{{ $livraison->statut }}</td>
                        <td class="py-2 px-4 border-b">
                            <form action="{{ route('livreur.deliveries.prendre', $livraison->id_livraison) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded">Prendre</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500">Aucune livraison en attente.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
