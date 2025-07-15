@extends('layouts.app')
@section('content')
@php use Illuminate\Support\Str; @endphp
@include('layouts.navbar')
<div class="container mx-auto py-4 flex justify-end">
    <a href="{{ route('panier.afficher') }}" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">Mon panier</a>
</div>
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Produits de {{ $commercant->nom ?? $commercant->name }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($produits as $produit)
            @if($produit->quantite > 0)
                <div class="border rounded-lg p-4 bg-white">
                    @if(!empty($produit->image_url))
                        <img src="{{ Str::startsWith($produit->image_url, '/storage/') ? asset(ltrim($produit->image_url, '/')) : asset('storage/' . $produit->image_url) }}" alt="Image du produit" class="mb-2 w-full h-40 object-cover rounded">
                    @endif
                    <h3 class="font-semibold text-lg mb-2">{{ $produit->nom ?? $produit->name }}</h3>
                    <p class="mb-1"><strong>Prix :</strong> {{ number_format($produit->prix ?? 0, 2) }} €</p>
                    <p class="mb-1"><strong>Description :</strong> {{ $produit->description ?? '-' }}</p>
                    <form action="{{ route('panier.ajouter', $produit->id_produits) }}" method="POST" class="mt-2 flex items-center gap-2">
                        @csrf
                        <input type="number" name="quantite" min="1" max="{{ $produit->quantite }}" value="1" class="w-16 border rounded px-2 py-1">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-3 rounded">Ajouter au panier</button>
                    </form>
                </div>
            @endif
        @empty
            <p>Aucun produit trouvé pour ce commerce.</p>
        @endforelse
    </div>
    <div class="mt-6">
        <a href="{{ route('commerces.index') }}" class="text-blue-600 hover:underline"> Retour à la liste des commerçants</a>
    </div>
</div>
@endsection 