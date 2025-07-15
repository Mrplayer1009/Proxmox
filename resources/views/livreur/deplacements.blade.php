@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <a href="{{ route('livreur.deplacement.create') }}" class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">Ajouter un déplacement</a>
    <h2 class="text-2xl font-bold mb-6">Mes déplacements</h2>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2">Destination</th>
                <th class="px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deplacements as $dep)
                <tr>
                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($dep->date)->format('d/m/y') }}</td>
                    <td class="border px-4 py-2">
                        {{ $adresses[$dep->lieu_arrivee]->rue ?? '' }},
                        {{ $adresses[$dep->lieu_arrivee]->ville ?? '' }}
                    </td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('livreur.deplacement.livraisons', $dep->id_planning) }}" class="bg-blue-500 text-blue-500 px-4 py-2 rounded">Voir les livraisons</a>
                        <a href="{{ route('livreur.deplacement.edit', $dep->id_planning) }}" class="bg-yellow-500 text-yellow-500 px-4 py-2 rounded ml-2">Modifier</a>
                        <form action="{{ route('livreur.deplacement.delete', $dep->id_planning) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded ml-2" onclick="return confirm('Supprimer ce déplacement ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 