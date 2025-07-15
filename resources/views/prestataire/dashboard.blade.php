@extends('layouts.app')
@section('content')
@include('layouts.navbar')
@if(isset($prestataire) && $prestataire && $prestataire->statut_validation == 'validé')
    <div class="container">
        <h2>Tableau de bord Prestataire</h2>
        <p>Bienvenue sur votre espace prestataire EcoDeli.</p>
        @php $user = Auth::user(); @endphp
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="mt-3">
            <strong>Statut d'agrément :</strong>
            <span class="text-success">Agréé</span>
        </div>
        <div class="mt-3">
            <strong>Nom entreprise :</strong> {{ $prestataire->nom_entreprise }}<br>
            <strong>SIRET :</strong> {{ $prestataire->siret }}<br>
            <strong>Adresse :</strong> {{ $prestataire->adresse }}<br>
            <strong>Téléphone :</strong> {{ $prestataire->telephone }}<br>
        </div>
        <hr>
        <div class="mt-4">
            <h4>Suivi des évaluations</h4>
            <p>Note moyenne : <strong>{{ $noteMoyenne ? number_format($noteMoyenne, 2) : 'Aucune note' }}</strong> / 5</p>
            <p>(Note donnée par les clients ayant utilisé vos services)</p>
        </div>
        <hr>
        <div class="mt-4">
            <h4>Validation du profil et des prestations</h4>
            <p>Votre profil et vos habilitations sont vérifiés par EcoDeli avant validation. Les types de prestations et tarifs pratiqués sont contrôlés. Les tarifs sont fixés ou négociés avec EcoDeli.</p>
            <p><em>(Section à compléter avec la gestion des habilitations et des types de prestations)</em></p>
        </div>
        <hr>
        <div class="mt-4">
            <h4>Calendrier de vos disponibilités</h4>
            <p>Consultez et gérez vos créneaux disponibles pour être affecté à des demandes clients.</p>
            <a href="{{ route('prestataire.calendrier') }}" class="btn btn-outline-primary btn-sm">Voir mon planning</a>
        </div>
        <hr>
        <div class="mt-4">
            <h4>Gestion de vos interventions</h4>
            <p>Visualisez et gérez vos interventions passées et à venir.</p>
            <a href="{{ route('prestataire.interventions') }}" class="btn btn-outline-primary btn-sm">Voir mes interventions</a>
        </div>
        <hr>
        <div class="mt-4">
            <h4>Facturation mensuelle</h4>
            <p>À la fin de chaque mois, une facture automatique est générée, récapitulant toutes vos prestations et vos gains. Elle est archivée et accessible à tout moment.</p>
            <a href="{{ route('prestataire.factures') }}" class="btn btn-outline-primary btn-sm">Voir mes factures</a>
        </div>
    </div>
@elseif(isset($prestataire) && $prestataire && empty($prestataire->piece_justificative))
    <div class="container mx-auto max-w-lg py-12">
        <div class="bg-white p-8 rounded shadow">
            <h2 class="text-2xl font-bold text-blue-800 mb-4">Vérification d'identité requise</h2>
            @if(session('success'))<div class="mb-4 text-green-600">{{ session('success') }}</div>@endif
            <form method="POST" action="{{ route('prestataire.upload_piece') }}" enctype="multipart/form-data">
                @csrf
                <label for="piece" class="block mb-2">Pièce justificative (PDF, JPG, PNG, max 4Mo)</label>
                <input type="file" name="piece" id="piece" class="mb-4" required>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Envoyer</button>
            </form>
        </div>
    </div>
@else
    <div class="container">
        <h2>Tableau de bord Prestataire</h2>
        <p>Bienvenue sur votre espace prestataire EcoDeli.</p>

        @php $user = Auth::user(); @endphp

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(isset(
$prestataire) && $prestataire)
            <div class="mt-3">
                <strong>Statut d'agrément :</strong>
                @if($prestataire->statut_validation == 'validé')
                    <span class="text-success">Agréé</span>
                @elseif($prestataire->statut_validation == 'refusé')
                    <span class="text-danger">Votre dossier a été refusé.</span>
                @else
                    <span class="text-warning">Votre dossier est en attente de validation.</span>
                @endif
            </div>
            <div class="mt-3">
                <strong>Nom entreprise :</strong> {{ $prestataire->nom_entreprise }}<br>
                <strong>SIRET :</strong> {{ $prestataire->siret }}<br>
                <strong>Adresse :</strong> {{ $prestataire->adresse }}<br>
                <strong>Téléphone :</strong> {{ $prestataire->telephone }}<br>
            </div>
            <hr>
            <div class="mt-4">
                <h4>Suivi des évaluations</h4>
                <p>Note moyenne : <strong>{{ $noteMoyenne ? number_format($noteMoyenne, 2) : 'Aucune note' }}</strong> / 5</p>
                <p>(Note donnée par les clients ayant utilisé vos services)</p>
            </div>
            <hr>
            <div class="mt-4">
                <h4>Validation du profil et des prestations</h4>
                <p>Votre profil et vos habilitations sont vérifiés par EcoDeli avant validation. Les types de prestations et tarifs pratiqués sont contrôlés. Les tarifs sont fixés ou négociés avec EcoDeli.</p>
                <p><em>(Section à compléter avec la gestion des habilitations et des types de prestations)</em></p>
            </div>
            <hr>
            <div class="mt-4">
                <h4>Calendrier de vos disponibilités</h4>
                <p>Consultez et gérez vos créneaux disponibles pour être affecté à des demandes clients.</p>
                <a href="{{ route('prestataire.calendrier') }}" class="btn btn-outline-primary btn-sm">Voir mon planning</a>
            </div>
            <hr>
            <div class="mt-4">
                <h4>Gestion de vos interventions</h4>
                <p>Visualisez et gérez vos interventions passées et à venir.</p>
                <a href="{{ route('prestataire.interventions') }}" class="btn btn-outline-primary btn-sm">Voir mes interventions</a>
            </div>
            <hr>
            <div class="mt-4">
                <h4>Facturation mensuelle</h4>
                <p>À la fin de chaque mois, une facture automatique est générée, récapitulant toutes vos prestations et vos gains. Elle est archivée et accessible à tout moment.</p>
                <a href="{{ route('prestataire.factures') }}" class="btn btn-outline-primary btn-sm">Voir mes factures</a>
            </div>
        @else
            <div class="mt-3">
                <a href="{{ route('prestataire.inscription.show') }}" class="btn btn-primary">S'inscrire comme prestataire</a>
            </div>
        @endif
    </div>
@endif
@endsection 