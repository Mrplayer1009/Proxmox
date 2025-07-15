<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use App\Models\Paiement;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Livraison;
use App\Models\Abonnement;
use Illuminate\Support\Str;

class AnnonceController extends Controller
{
    public function showStripePayment($annonceId)
    {
        $annonce = Annonce::findOrFail($annonceId);
        return view('annonces.stripe_payer', compact('annonce'));
    }

    public function stripeIntent(Request $request, $annonceId)
    {
        $annonce = Annonce::findOrFail($annonceId);
        Stripe::setApiKey(config('stripe.secret'));
        $intent = PaymentIntent::create([
            'amount' => intval($annonce->prix * 100),
            'currency' => 'eur',
            'metadata' => [
                'user_id' => Auth::id(),
                'annonce_id' => $annonce->id,
            ],
        ]);
        return response()->json(['clientSecret' => $intent->client_secret]);
    }

    public function stripePayer($annonceId)
    {
        $annonce = Annonce::findOrFail($annonceId);
        $user = auth()->user();
        $id_utilisateur = $user->id_utilisateur ?? $user->id;
        $abonnement = Abonnement::where('id_utilisateur', $id_utilisateur)->latest()->first();

        Stripe::setApiKey(config('stripe.secret'));
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $annonce->titre,
                    ],
                    'unit_amount' => intval($annonce->prix * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('annonces.stripe_success', ['annonce' => $annonce->id_annonce ?? $annonce->id], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('annonces.index'),
        ]);

        return view('annonces.stripe_paiement', [
            'sessionId' => $session->id,
            'publicKey' => config('stripe.key'),
            'annonce' => $annonce,
            'abonnement' => $abonnement,
        ]);
    }

    public function stripeSuccess(Request $request, $annonceId)
    {
        $annonce = Annonce::findOrFail($annonceId);
        $user = auth()->user();
        $id_utilisateur = $user->id_utilisateur ?? $user->id;
        $abonnement = Abonnement::where('id_utilisateur', $id_utilisateur)->latest()->first();
        $session_id = $request->query('session_id');

        Stripe::setApiKey(config('stripe.secret'));
        $session = StripeSession::retrieve($session_id);

        if ($session->payment_status === 'paid') {
            // Paiement
            Paiement::create([
                'id_utilisateur' => $id_utilisateur,
                'montant' => $annonce->prix,
                'info' => 'annonce ' . $annonce->titre,
                'date' => now(),
                'methode' => 'stripe',
                'statut' => 'validé',
            ]);

            // livraison
            $id_adresse_depart = $annonce->id_addresse ?? null;
            $id_adresse_arrivee = $user->adresse ?? null;
            // Debug : afficher la valeur de l'adresse d'arrivée
            //dd(['id_adresse_arrivee' => $id_adresse_arrivee, 'user' => $user]);

            Livraison::create([
                'id_annonce' => $annonce->id_annonce ?? $annonce->id,
                'id_livreur' => null,
                'id_utilisateur' => $id_utilisateur,
                'id_adresse_depart' => $id_adresse_depart,
                'id_adresse_arrivee' => $id_adresse_arrivee,
                'date_livraison' => now()->addDays($abonnement && $abonnement->nom === 'Deluxe' ? 1 : ($abonnement && $abonnement->nom === 'Premium' ? 2 : 4)),
                'code_validation' => strtoupper(Str::random(8)),
                'poids' => $annonce->poids,
                'fragile' => $annonce->fragile,
                'statut' => 'en_attente',
                'contenu' => $annonce->titre,
                'date' => now(),
                'modalite' => $abonnement->nom ?? 'Standard',
                'type' => $annonce->type,
            ]);

            // Mettre à jour le statut de l'annonce à 'terminée'
            $annonce->statut = 'terminée';
            $annonce->save();

            return redirect()->route('annonces.index')->with('success', 'Paiement et livraison créés !');
        } else {
            return redirect()->route('annonces.index')->with('error', 'Paiement non validé.');
        }
    }
}
