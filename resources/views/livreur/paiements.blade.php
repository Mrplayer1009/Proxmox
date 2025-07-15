@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Mes paiements</h2>
    <div class="mb-4">
        <a href="{{ route('livreur.paiements.pdf') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Télécharger en PDF</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">ID</th>
                    <th class="py-2 px-4 border-b">Montant</th>
                    <th class="py-2 px-4 border-b">Date paiement</th>
                    <th class="py-2 px-4 border-b">Méthode</th>
                    <th class="py-2 px-4 border-b">Statut</th>
                    <th class="py-2 px-4 border-b">Créé le</th>
                    <th class="py-2 px-4 border-b">MAJ le</th>
                    <th class="py-2 px-4 border-b">PDF</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paiements as $paiement)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $paiement->id_paiement }}</td>
                        <td class="py-2 px-4 border-b">{{ number_format($paiement->montant, 2, ',', ' ') }} €</td>
                        <td class="py-2 px-4 border-b">{{ $paiement->date_paiement }}</td>
                        <td class="py-2 px-4 border-b">{{ $paiement->methode_paiement }}</td>
                        <td class="py-2 px-4 border-b">{{ ucfirst($paiement->statut_paiement) }}</td>
                        <td class="py-2 px-4 border-b">{{ $paiement->cree_le }}</td>
                        <td class="py-2 px-4 border-b">{{ $paiement->modifie_le }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('livreur.paiement.pdf', $paiement->id_paiement) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded" title="Télécharger ce paiement en PDF">PDF</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-4 text-center text-gray-500">Aucun paiement trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 