<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PanierController extends Controller
{
    public function ajouter(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);
        $quantite = max(1, min($request->input('quantite', 1), $produit->quantite));
        $panier = session()->get('panier', []);
        if (isset($panier[$id])) {
            $panier[$id]['quantite'] += $quantite;
            if ($panier[$id]['quantite'] > $produit->quantite) {
                $panier[$id]['quantite'] = $produit->quantite;
            }
        } else {
            $panier[$id] = [
                'id' => $produit->id_produits,
                'nom' => $produit->nom,
                'prix' => $produit->prix,
                'quantite' => $quantite,
            ];
        }
        session(['panier' => $panier]);
        return redirect()->back()->with('success', 'Produit ajouté au panier.');
    }

    public function afficher()
    {
        $panier = session('panier', []);
        $total = collect($panier)->sum(function($item) { return $item['prix'] * $item['quantite']; });
        return view('panier.afficher', compact('panier', 'total'));
    }

    public function supprimer($id)
    {
        $panier = session('panier', []);
        unset($panier[$id]);
        session(['panier' => $panier]);
        return redirect()->route('panier.afficher')->with('success', 'Produit retiré du panier.');
    }

    public function paiement()
    {
        $panier = session('panier', []);
        $total = collect($panier)->sum(function($item) { return $item['prix'] * $item['quantite']; });
        return view('panier.paiement', compact('panier', 'total'));
    }

    public function payer(Request $request)
    {
        $panier = session('panier', []);
        $total = collect($panier)->sum(function($item) { return $item['prix'] * $item['quantite']; });
        // Paiement Stripe simulé ici (à remplacer par l'intégration réelle)
        foreach ($panier as $id => $item) {
            $produit = \App\Models\Produit::find($id);
            if ($produit && $produit->quantite >= $item['quantite']) {
                $produit->quantite -= $item['quantite'];
                $produit->save();
            }
        }
        // Insertion du paiement dans la table paiement
        \App\Models\Paiement::create([
            'id_utilisateur' => auth()->user()->id_utilisateur ?? auth()->id(),
            'montant' => $total,
            'date' => now(),
            'methode' => 'stripe',
            'statut' => 'validé',
        ]);

        $contenu = collect($panier)->map(function($item) {
            return $item['quantite'] . 'x ' . $item['nom'];
        })->implode(', ');

        $user = auth()->user();
        $id_utilisateur = $user->id_utilisateur ?? $user->id;
        $id_adresse_arrivee = $user->adresse ?? null;

        // Récupérer l'adresse du commerçant du premier produit du panier
        $premierProduit = reset($panier);
        $produit = \App\Models\Produit::find($premierProduit['id']);
        $id_commercant = $produit->id_commercant ?? null;
        $commercant = \App\Models\Commercant::find($id_commercant);
        $id_adresse_depart = $commercant->adresse ?? null;

        \App\Models\Livraison::create([
            'id_utilisateur' => $id_utilisateur,
            'id_livreur' => null,
            'id_annonce' => null,
            'id_adresse_depart' => $id_adresse_depart,
            'id_adresse_arrivee' => $id_adresse_arrivee,
            'date_livraison' => now()->addDays(2),
            'code_validation' => strtoupper(Str::random(8)),
            'poids' => 0,
            'fragile' => 0,
            'statut' => 'en_attente',
            'contenu' => $contenu,
            'date' => now(),
            'modalite' => 'Panier',
            'type' => 'panier',
        ]);

        session()->forget('panier');
        return redirect()->route('panier.afficher')->with('success', 'Paiement effectué, stock mis à jour et paiement enregistré.');
    }

    public function createPaymentIntent(Request $request)
    {
        $panier = session('panier', []);
        $total = collect($panier)->sum(function($item) { return $item['prix'] * $item['quantite']; });
        $amount = intval($total * 100); // en centimes
        \Stripe\Stripe::setApiKey(config('stripe.secret'));
        $intent = \Stripe\PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'eur',
            'metadata' => [
                'user_id' => auth()->id(),
            ],
        ]);
        return response()->json(['clientSecret' => $intent->client_secret]);
    }
} 