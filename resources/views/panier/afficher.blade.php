@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Mon panier</h2>
    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if(empty($panier))
        <p>Votre panier est vide.</p>
    @else
        <table class="min-w-full bg-white border border-gray-200 mb-6">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Produit</th>
                    <th class="py-2 px-4 border-b">Prix</th>
                    <th class="py-2 px-4 border-b">Quantité</th>
                    <th class="py-2 px-4 border-b">Total</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($panier as $item)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $item['nom'] }}</td>
                        <td class="py-2 px-4 border-b">{{ number_format($item['prix'], 2) }} €</td>
                        <td class="py-2 px-4 border-b">{{ $item['quantite'] }}</td>
                        <td class="py-2 px-4 border-b">{{ number_format($item['prix'] * $item['quantite'], 2) }} €</td>
                        <td class="py-2 px-4 border-b">
                            <form action="{{ route('panier.supprimer', $item['id']) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mb-4 text-right">
            <span class="font-bold text-lg">Total : {{ number_format($total, 2) }} €</span>
        </div>
        <div class="text-right">
            <a href="{{ route('panier.paiement') }}" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">Payer</a>
        </div>
    @endif
</div>
@endsection 