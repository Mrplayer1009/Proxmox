@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Tous les commerçants</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($commercants as $commercant)
            <div class="border rounded-lg p-4 bg-white">
                <h3 class="font-semibold text-lg mb-2">{{ $commercant->nom ?? $commercant->name }}</h3>
                <p class="mb-1"><strong>Email :</strong> {{ $commercant->email }}</p>
                <a href="{{ route('commerces.produits', $commercant->id_commercant) }}" class="mt-2 inline-block bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition">Voir commerce</a>
            </div>
        @endforeach
    </div>
</div>
@endsection 