<h1>Facture mensuelle</h1>
<p><strong>Prestataire :</strong> {{ $facture->prestataire->nom_entreprise ?? '' }}</p>
<p><strong>Mois :</strong> {{ $facture->mois }}</p>
<p><strong>Montant total :</strong> {{ number_format($facture->montant, 2, ',', ' ') }} €</p>
<p><strong>Date de génération :</strong> {{ $facture->date_generation }}</p>
<hr>
<p>Date de génération du PDF : {{ date('d/m/Y') }}</p> 