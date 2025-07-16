@extends('layouts.app')
@section('content')
@include('layouts.navbar')
<div class="max-w-2xl mx-auto mt-10 bg-white rounded-xl shadow p-8">
    <h2 class="text-2xl font-bold text-orange-600 mb-6">Tableau de bord Client</h2>
    <div class="flex flex-col gap-4">
        <a href="{{ route('client.annonces.create') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded transition">Créer une annonce</a>
        <a href="{{ route('client.annonces') }}" class="bg-orange-100 hover:bg-orange-200 text-orange-700 font-semibold py-2 px-4 rounded transition">Voir mes annonces</a>
        <a href="{{ route('client.paiements') }}" class="bg-green-100 hover:bg-green-200 text-green-700 font-semibold py-2 px-4 rounded transition">Mes paiements</a>
        <a href="{{ route('client.interventions') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded block mb-4">Mes interventions</a>
    </div>
</div>
@endsection 