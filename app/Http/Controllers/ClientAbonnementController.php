<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class ClientAbonnementController extends Controller
{
    // affiche abonnement
    public function index()
    {
        $abonnement = null;
        if (auth()->check()) {
            $abonnement = Abonnement::where('id_utilisateur', auth()->id())->latest()->first();
        }
        $catalogue = [
            'basic' => [
                'nom' => 'Basic',
                'prix' => 9.99,
                'avantages' => 'Livraison standard, support classique',
            ],
            'premium' => [
                'nom' => 'Premium',
                'prix' => 19.99,
                'avantages' => 'Livraison express, support prioritaire',
            ],
            'deluxe' => [
                'nom' => 'Deluxe',
                'prix' => 29.99,
                'avantages' => 'Livraison ultra-rapide, support VIP, cadeaux mensuels',
            ],
        ];
        return view('client.abonnement', compact('abonnement', 'catalogue'));
    }

    // isi affiche la page stripe   
    public function paiement(Request $request)
    {
        $type = $request->input('type');
        $catalogue = [
            'basic' => [
                'nom' => 'Basic',
                'prix' => 9.99,
                'avantages' => 'Livraison standard, support classique',
            ],
            'premium' => [
                'nom' => 'Premium',
                'prix' => 19.99,
                'avantages' => 'Livraison express, support prioritaire',
            ],
            'deluxe' => [
                'nom' => 'Deluxe',
                'prix' => 29.99,
                'avantages' => 'Livraison ultra-rapide, support VIP, cadeaux mensuels',
            ],
        ];
        if (!isset($catalogue[$type])) {
            return redirect()->route('client.abonnement')->with('error', "Type d'abonnement invalide.");
        }
        $abo = $catalogue[$type];
        Stripe::setApiKey(config('stripe.secret'));
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $abo['nom'],
                    ],
                    'unit_amount' => intval($abo['prix'] * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('client.abonnement.paiement.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}&type=' . $type,
            'cancel_url' => route('client.abonnement'),
        ]);
        return view('client.abonnement_paiement', [
            'sessionId' => $session->id,
            'publicKey' => config('stripe.key'),
            'abo' => $abo,
        ]);
    }

    public function paiementSuccess(Request $request)
    {
        $session_id = $request->query('session_id');
        $type = $request->query('type');
        $catalogue = [
            'basic' => [
                'nom' => 'Basic',
                'prix' => 9.99,
                'avantages' => 'Livraison standard, support classique',
            ],
            'premium' => [
                'nom' => 'Premium',
                'prix' => 19.99,
                'avantages' => 'Livraison express, support prioritaire',
            ],
            'deluxe' => [
                'nom' => 'Deluxe',
                'prix' => 29.99,
                'avantages' => 'Livraison ultra-rapide, support VIP, cadeaux mensuels',
            ],
        ];
        if (!isset($catalogue[$type])) {
            return redirect()->route('client.abonnement')->with('error', "Type d'abonnement invalide.");
        }
        $abo = $catalogue[$type];
        Stripe::setApiKey(config('stripe.secret'));
        $session = StripeSession::retrieve($session_id);
        if ($session->payment_status === 'paid') {
            // Enregistre le paiement
            Paiement::create([
                'id_utilisateur' => auth()->id(),
                'montant' => $abo['prix'],
                'info' => 'abonnement ' . $abo['nom'],
                'date' => now(),
                'methode' => 'stripe',
                'statut' => 'validé',
            ]);
            // Crée l'abonnement
            Abonnement::create([
                'id_utilisateur' => auth()->id(),
                'nom' => $abo['nom'],
                'date_debut' => now(),
                'date_fin' => now()->addYear(),
                'statut' => 'actif',
                'prix' => $abo['prix'],
            ]);
            return redirect()->route('client.abonnement')->with('success', 'Paiement réussi, abonnement activé !');
        } else {
            return redirect()->route('client.abonnement')->with('error', 'Paiement non validé.');
        }
    }
} 