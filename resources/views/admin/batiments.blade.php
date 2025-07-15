@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Liste des bâtiments</h2>
    <a href="{{ route('admin.batiment.create') }}" class="bg-green-600 text-green-500 px-4 py-2 rounded mb-4 inline-block">Ajouter un bâtiment</a>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Nom</th>
                <th class="px-4 py-2">Rue</th>
                <th class="px-4 py-2">Ville</th>
                <th class="px-4 py-2">Code postal</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($batiments as $batiment)
                <tr>
                    <td class="border px-4 py-2">{{ $batiment->nom }}</td>
                    <td class="border px-4 py-2">{{ $batiment->addresse->rue ?? '' }}</td>
                    <td class="border px-4 py-2">{{ $batiment->addresse->ville ?? '' }}</td>
                    <td class="border px-4 py-2">{{ $batiment->addresse->code_postal ?? '' }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('admin.batiment.edit', $batiment->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">Modifier</a>
                        <form action="{{ route('admin.batiment.delete', $batiment->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded ml-2" onclick="return confirm('Supprimer ce bâtiment ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 