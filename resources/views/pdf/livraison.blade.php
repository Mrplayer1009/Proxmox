<h1>{{ $title }}</h1>
<p><strong>Annonce :</strong> {{ $livraison->annonce->description ?? '' }}</p>
<p><strong>Départ :</strong> {{ $livraison->annonce->lieu_depart ?? '' }}</p>
<p><strong>Arrivée :</strong> {{ $livraison->annonce->lieu_arrivee ?? '' }}</p>
<p><strong>Date livraison :</strong> {{ $livraison->date_livraison }}</p>
<p><strong>Livreur :</strong> {{ $livraison->livreur->nom ?? 'Non assigné' }} {{ $livraison->livreur->prenom ?? '' }}</p>
<p><strong>Code validation :</strong> {{ $livraison->code_validation }}</p>
<p><strong>Statut :</strong> {{ $livraison->statut }}</p>
<hr>
<p>Date de génération : {{ date('d/m/Y') }}</p> 