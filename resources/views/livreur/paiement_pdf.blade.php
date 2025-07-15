<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px 8px; font-size: 13px; }
        th { background: #f0f0f0; }
        h2 { margin-bottom: 0; }
    </style>
</head>
<body>
    <h2>Paiement du livreur</h2>
    @if(isset($livreur))
        <p>Livreur : <strong>{{ $livreur->id_livreur }}</strong></p>
    @endif
    @if($paiement)
    <table>
        <tr><th>ID</th><td>{{ $paiement->id_paiement }}</td></tr>
        <tr><th>Montant</th><td>{{ number_format($paiement->montant, 2, ',', ' ') }} €</td></tr>
        <tr><th>Date paiement</th><td>{{ $paiement->date_paiement }}</td></tr>
        <tr><th>Méthode</th><td>{{ $paiement->methode_paiement }}</td></tr>
        <tr><th>Statut</th><td>{{ ucfirst($paiement->statut_paiement) }}</td></tr>
        <tr><th>Créé le</th><td>{{ $paiement->cree_le }}</td></tr>
        <tr><th>MAJ le</th><td>{{ $paiement->modifie_le }}</td></tr>
    </table>
    @else
        <p>Paiement introuvable.</p>
    @endif
</body>
</html> 