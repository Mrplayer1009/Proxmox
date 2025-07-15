@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white rounded-xl shadow p-8">
    <h2 class="text-2xl font-bold text-green-600 mb-6">Mes paiements</h2>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Méthode</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">PDF</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($paiements as $paiement)
            <tr>
                <td class="px-4 py-2">{{ $paiement->id_paiement }}</td>
                <td class="px-4 py-2">{{ number_format($paiement->montant, 2, ',', ' ') }} €</td>
                <td class="px-4 py-2">{{ $paiement->date }}</td>
                <td class="px-4 py-2">{{ $paiement->methode }}</td>
                <td class="px-4 py-2">{{ $paiement->statut }}</td>
                <td class="px-4 py-2">
                    <a href="{{ route('pdf.paiement', $paiement->id_paiement) }}" target="_blank" class="bg-orange-500 hover:bg-orange-600 text-orange-500 px-3 py-1 rounded text-xs font-semibold">PDF</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 