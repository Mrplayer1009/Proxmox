<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px 8px; font-size: 12px; }
        th { background: #f0f0f0; }
        h2 { margin-bottom: 0; }
    </style>
</head>
<body>
    <h2>Paiements du livreur</h2>
    @if(isset($livreur))
        <p>Livreur : <strong>{{ $livreur->id_livreur }}</strong></p>
    @endif
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Montant</th>
                <th>Date paiement</th>
                <th>Méthode</th>
                <th>Statut</th>
                <th>Créé le</th>
                <th>MAJ le</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paiements as $paiement)
                <tr>
                    <td>{{ $paiement->id_paiement }}</td>
                    <td>{{ number_format($paiement->montant, 2, ',', ' ') }} €</td>
                    <td>{{ $paiement->date_paiement }}</td>
                    <td>{{ $paiement->methode_paiement }}</td>
                    <td>{{ ucfirst($paiement->statut_paiement) }}</td>
                    <td>{{ $paiement->cree_le }}</td>
                    <td>{{ $paiement->modifie_le }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Aucun paiement trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html> 