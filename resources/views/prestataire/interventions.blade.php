@extends('layouts.app')
@section('content')
@include('layouts.navbar')
<div class="container">
    <h2>Gestion de mes interventions</h2>
    @if($interventions->isEmpty())
        <p>Aucune intervention à afficher.</p>
    @else
        <ul class="list-group">
            @foreach($interventions as $intervention)
                <li class="list-group-item d-flex justify-content-between align-items-center {{ $intervention->statut === 'annulée' ? 'text-muted' : '' }}">
                    <span>
                        <strong>{{ \Carbon\Carbon::parse($intervention->date)->format('d/m/Y') }}</strong>
                        à {{ substr($intervention->heure_debut, 0, 5) }}
                        — {{ $intervention->titre ?? 'Prestation inconnue' }}
                        @if($intervention->statut === 'annulée')
                            <span class="badge bg-danger ms-2">Annulée</span>
                        @endif
                    </span>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalIntervention{{ $intervention->id_reservation }}">
                        Détails
                    </button>
                </li>
            @endforeach
        </ul>
        @foreach($interventions as $intervention)
            <!-- Modal -->
            <div class="modal fade" id="modalIntervention{{ $intervention->id_reservation }}" tabindex="-1" aria-labelledby="modalLabel{{ $intervention->id_reservation }}" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel{{ $intervention->id_reservation }}">Détail de l'intervention</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                  </div>
                  <div class="modal-body">
                    <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($intervention->date)->format('d/m/Y') }}</p>
                    <p><strong>Heure :</strong> {{ substr($intervention->heure_debut, 0, 5) }} - {{ substr($intervention->heure_fin, 0, 5) }}</p>
                    <p><strong>Prestation :</strong> {{ $intervention->titre ?? 'Prestation inconnue' }}</p>
                    <p><strong>Client :</strong> {{ $intervention->client->nom ?? 'Client inconnu' }}</p>
                    @php $adresse = $intervention->id_addresse ? \App\Models\Addresse::find($intervention->id_addresse) : null; @endphp
                    @if($adresse)
                        <p><strong>Adresse :</strong> {{ $adresse->ville }}, {{ $adresse->rue }}, {{ $adresse->code_postal }}</p>
                    @endif
                    <p><strong>Statut :</strong> {{ ucfirst($intervention->statut) }}</p>
                    <p><strong>Créée le :</strong> {{ $intervention->created_at ? $intervention->created_at->format('d/m/Y H:i') : '-' }}</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    @if($intervention->statut !== 'annulée')
                        <form action="{{ route('prestataire.annuler_intervention', $intervention->id_reservation) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette intervention ?')">Annuler</button>
                        </form>
                    @else
                        <span class="text-danger">Intervention annulée</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
        @endforeach
    @endif
</div>
@endsection 