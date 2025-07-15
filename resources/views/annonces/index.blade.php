@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Annonces en cours</h2>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Nom</th>
                <th class="px-4 py-2">Utilisateur</th>
                <th class="px-4 py-2">Prix</th>
                <th class="px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($annonces as $annonce)
                <tr>
                    <td class="border px-4 py-2">{{ $annonce->id_annonce ?? $annonce->id }}</td>
                    <td class="border px-4 py-2">{{ $annonce->titre }}</td>
                    <td class="border px-4 py-2">{{ $annonce->utilisateur->nom ?? $annonce->utilisateur->prenom ?? 'N/A' }}</td>
                    <td class="border px-4 py-2">{{ number_format($annonce->prix, 2) }} €</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('annonces.stripe_payer', ['annonce' => $annonce->id_annonce ?? $annonce->id]) }}" class="bg-orange-500 text-blue-500 px-4 py-2 rounded hover:bg-orange-600 transition">Payer</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 