@extends('layouts.app')
@section('content')
@include('layouts.navbar')
<div class="container mx-auto py-8 max-w-lg">
    <h2 class="text-2xl font-bold mb-6">Noter la prestation</h2>
    <form method="POST" action="{{ route('client.reservation.noter.submit', $reservation->id_reservation) }}">
        @csrf
        <div class="mb-4">
            <label for="note" class="block mb-1 font-semibold">Note (1 à 5)</label>
            <select name="note" id="note" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Sélectionner --</option>
                @for($i=1; $i<=5; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="mb-4">
            <label for="commentaire" class="block mb-1 font-semibold">Commentaire (optionnel)</label>
            <textarea name="commentaire" id="commentaire" class="w-full border rounded px-3 py-2"></textarea>
        </div>
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Envoyer la note</button>
    </form>
</div>
@endsection 