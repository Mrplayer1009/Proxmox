<h1>{{ $title }}</h1>
<p><strong>Nom :</strong> {{ $paiement->utilisateur->nom ?? '' }} {{ $paiement->utilisateur->prenom ?? '' }}</p>
<p><strong>Montant :</strong> {{ number_format($paiement->montant, 2, ',', ' ') }} €</p>
<p><strong>Date :</strong> {{ $paiement->date }}</p>
<p><strong>Méthode :</strong> {{ $paiement->methode }}</p>
<p><strong>Statut :</strong> {{ $paiement->statut }}</p>
<hr>
<p>Date de génération : {{ date('d/m/Y') }}</p> 