<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use Illuminate\Support\Facades\Auth;

class CommercantController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        // Recherche du commerçant lié à l'utilisateur (par email)
        $commercant = \App\Models\Commercant::where('email', $user->email)->first();
        if (!$commercant) {
            // Affiche le formulaire pour créer l'entreprise et le contrat
            return view('commercant.entreprise_form');
        }
        // Vérifie la présence d'au moins un contrat actif
        $contratActif = $commercant->contrats()->where('statut', 'actif')->first();
        if (!$contratActif) {
            // Affiche le formulaire pour créer un contrat
            return view('commercant.contrat_form', compact('commercant'));
        }
        // Dashboard normal
        $produits = $commercant->produits;
        return view('commercant.dashboard', compact('produits', 'commercant'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'nullable|numeric',
            'quantite' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();
        $commercant = \App\Models\Commercant::where('email', $user->email)->first();
        if (!$commercant) {
            return redirect()->back()->with('error', 'Aucun commerçant associé à cet utilisateur.');
        }

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('produits', 'public');
            $imageUrl = '/storage/' . $imagePath;
        }

        \App\Models\Produit::create([
            'id_commercant' => $commercant->id_commercant,
            'nom' => $request->nom,
            'description' => $request->description,
            'prix' => $request->prix,
            'quantite' => $request->quantite ?? 0,
            'image_url' => $imageUrl,
        ]);

        return redirect()->route('commercant.dashboard')->with('success', 'Produit créé avec succès.');
    }

    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'nullable|numeric',
            'quantite' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();
        $commercant = \App\Models\Commercant::where('email', $user->email)->first();
        if (!$commercant) {
            abort(403, 'Aucun commerçant associé à cet utilisateur.');
        }

        $produit = Produit::where('id_produits', $id)->where('id_commercant', $commercant->id_commercant)->firstOrFail();

        $imageUrl = $produit->image_url;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('produits', 'public');
            $imageUrl = '/storage/' . $imagePath;
        }

        $produit->update([
            'nom' => $request->nom,
            'description' => $request->description,
            'prix' => $request->prix,
            'quantite' => $request->quantite ?? 0,
            'image_url' => $imageUrl,
        ]);

        return redirect()->route('commercant.dashboard')->with('success', 'Produit mis à jour avec succès.');
    }

    public function deleteProduct($id)
    {
        $user = Auth::user();
        $commercant = \App\Models\Commercant::where('email', $user->email)->first();
        if (!$commercant) {
            abort(403, 'Aucun commerçant associé à cet utilisateur.');
        }
        $produit = Produit::where('id_produits', $id)->where('id_commercant', $commercant->id_commercant)->firstOrFail();
        $produit->delete();
        return redirect()->route('commercant.dashboard')->with('success', 'Produit supprimé avec succès.');
    }

    public function toggleAffiche($id)
    {
        $user = Auth::user();
        $commercant = \App\Models\Commercant::where('email', $user->email)->first();
        if (!$commercant) {
            abort(403, 'Aucun commerçant associé à cet utilisateur.');
        }
        $produit = Produit::where('id_produits', $id)->where('id_commercant', $commercant->id_commercant)->firstOrFail();
        $produit->affiche = !$produit->affiche;
        $produit->save();
        return redirect()->route('commercant.dashboard')->with('success', 'Produit mis à jour avec succès.');
    }

    public function index()
    {
        $commercants = \App\Models\Commercant::all();
        return view('commerces.index', compact('commercants'));
    }

    public function produitsCommerce($id)
    {
        $commercant = \App\Models\Commercant::findOrFail($id);
        $produits = $commercant->produits ?? [];
        return view('commerces.produits', compact('commercant', 'produits'));
    }

    public function storeEntreprise(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:commercants,email',
            'telephone' => 'nullable|string',
            'adresse' => 'nullable|string',
        ]);
        $data = $request->only(['nom', 'email', 'telephone', 'adresse']);
        $data['id_utilisateur'] = Auth::user()->id_utilisateur ?? Auth::id();
        $commercant = \App\Models\Commercant::create($data);
        return redirect()->route('commercant.dashboard')->with('success', 'Entreprise enregistrée. Veuillez créer un contrat.');
    }

    public function storeContrat(Request $request, $id)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'fichier_pdf' => 'nullable|file|mimes:pdf|max:4096',
        ]);
        $contrat = new \App\Models\Contrat();
        $contrat->id_commercant = $id;
        $contrat->date_debut = $request->date_debut;
        $contrat->date_fin = $request->date_fin;
        $contrat->statut = 'actif';
        if ($request->hasFile('fichier_pdf')) {
            $path = $request->file('fichier_pdf')->store('contrats', 'public');
            $contrat->fichier_pdf = $path;
        }
        $contrat->save();
        return redirect()->route('commercant.dashboard')->with('success', 'Contrat créé avec succès.');
    }
}
