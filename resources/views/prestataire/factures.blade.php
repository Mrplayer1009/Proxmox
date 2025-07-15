@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Mes factures mensuelles</h2>
    @if(isset(
$factures) && count($factures) > 0)
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Mois</th>
                    <th>Date de génération</th>
                    <th>Montant total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($factures as $facture)
                    <tr>
                        <td>{{ $facture->mois }}</td>
                        <td>{{ $facture->date_generation }}</td>
                        <td>{{ number_format($facture->montant, 2) }} €</td>
                        <td>
                            <a href="{{ route('prestataire.factures.pdf', $facture->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">Télécharger PDF</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-end">Total général</th>
                    <th colspan="2">{{ number_format($factures->sum('montant'), 2) }} €</th>
                </tr>
            </tfoot>
        </table>
    @else
        <p>Aucune facture disponible pour le moment.</p>
    @endif
</div>
@endsection 