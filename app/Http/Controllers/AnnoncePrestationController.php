<?php

namespace App\Http\Controllers;

use App\Models\AnnoncePrestation;
use Illuminate\Http\Request;

class AnnoncePrestationController extends Controller
{
    public function index()
    {
        $annonces = AnnoncePrestation::with(['prestation', 'utilisateur'])->latest()->get();
        return view('annonces.annonce_prestation', compact('annonces'));
    }

    public function create()
    {
        // On suppose que le prestataire peut choisir une prestation existante ou saisir un titre/description
        $prestataire = auth()->user();
        $prestations = \App\Models\Prestation::where('id_prestataire', $prestataire->id_utilisateur ?? $prestataire->id)->get();
        return view('prestataire.create_annonce_prestation', compact('prestations'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
        ]);
        $user = auth()->user();
        $prestataire = \App\Models\Prestataire::where('id_utilisateur', $user->id_utilisateur ?? $user->id)->first();
        $annonce = \App\Models\AnnoncePrestation::create([
            'id_prestataire' => $prestataire->id_prestataire,
            'id_utilisateur' => $user->id_utilisateur ?? $user->id,
            'titre' => $request->titre,
            'description' => $request->description,
            'prix' => $request->prix,
            'statut' => 'en_cours',
        ]);
        return redirect()->route('annonces.prestations')->with('success', 'Annonce prestation créée !');
    }

    public function prendre($id)
    {
        $annonce = \App\Models\AnnoncePrestation::findOrFail($id);
        return view('annonces.prendre_prestation', compact('annonce'));
    }

    public function payer(\Illuminate\Http\Request $request, $id)
    {
        $annonce = \App\Models\AnnoncePrestation::findOrFail($id);
        $request->validate([
            'date' => 'required|date',
            'heures' => 'required|integer|min:1',
        ]);
        $user = auth()->user();
        $prixTotal = $annonce->prix * $request->heures;

        // Calcul heure_debut et heure_fin (heure_debut = 09:00, heure_fin = heure_debut + nb heures)
        $heureDebut = '09:00:00';
        $heureFin = date('H:i:s', strtotime($heureDebut . ' + ' . $request->heures . ' hours'));

        // Création de la réservation
        $reservation = new \App\Models\Reservation();
        $reservation->id_prestation = $annonce->id_annonce_prestation; // ou id_prestation si tu veux lier à une vraie prestation
        $reservation->id_client = $user->id_utilisateur ?? $user->id;
        $reservation->date = $request->date;
        $reservation->heure_debut = $heureDebut;
        $reservation->heure_fin = $heureFin;
        $reservation->statut = 'en_attente';
        $reservation->save();

        // Stripe Checkout
        \Stripe\Stripe::setApiKey(config('stripe.secret'));
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $annonce->titre,
                    ],
                    'unit_amount' => intval($prixTotal * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('annonces.prestations.success', ['reservation' => $reservation->id_reservation], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('annonces.prestations.prendre', $annonce->id_annonce_prestation),
        ]);

        // Stocker l'id de session Stripe dans la réservation pour vérification après paiement
        $reservation->stripe_session_id = $session->id;
        $reservation->save();

        return redirect($session->url);
    }

    public function success(\Illuminate\Http\Request $request, $reservationId)
    {
        $reservation = \App\Models\Reservation::findOrFail($reservationId);
        $annonce = \App\Models\AnnoncePrestation::find($reservation->id_prestation);
        $session_id = $request->query('session_id');
        \Stripe\Stripe::setApiKey(config('stripe.secret'));
        $session = \Stripe\Checkout\Session::retrieve($session_id);
        if ($session->payment_status === 'paid') {
            // Paiement
            \App\Models\Paiement::create([
                'id_utilisateur' => $reservation->id_client,
                'montant' => $annonce->prix * ((strtotime($reservation->heure_fin) - strtotime($reservation->heure_debut))/3600),
                'date' => now(),
                'methode' => 'stripe',
                'statut' => 'validé',
                'info' => 'Réservation prestation: ' . $annonce->titre,
            ]);
            $reservation->statut = 'validée';
            $reservation->save();
            return redirect()->route('annonces.prestations')->with('success', 'Réservation et paiement validés !');
        } else {
            $reservation->statut = 'annulée';
            $reservation->save();
            return redirect()->route('annonces.prestations')->with('error', 'Paiement non validé.');
        }
    }
} 