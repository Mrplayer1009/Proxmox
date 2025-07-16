<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur;
use App\Models\Annonce;
use App\Models\Paiement;
use App\Models\Livraison;
use App\Models\Prestataire;

class AdminController extends Controller
{
    public function dashboard()
    {
        $utilisateurs = Utilisateur::all();
        $annonces = Annonce::all();
        $paiements = Paiement::all();
        $livraisons = Livraison::all();
        $prestataires = Prestataire::all();
        return view('admin.all', compact('utilisateurs', 'annonces', 'paiements', 'livraisons', 'prestataires'));
    }

    public function prestataires()
    {
        $prestataires = Prestataire::all();
        return view('admin.prestataires', compact('prestataires'));
    }

    public function validerPrestataire($id)
    {
        $prestataire = Prestataire::findOrFail($id);
        $prestataire->statut_validation = 'validé';
        $prestataire->save();
        return redirect()->route('admin.prestataires')->with('success', 'Prestataire validé.');
    }

    public function refuserPrestataire($id)
    {
        $prestataire = Prestataire::findOrFail($id);
        $prestataire->statut_validation = 'refusé';
        $prestataire->save();
        return redirect()->route('admin.prestataires')->with('success', 'Prestataire refusé.');
    }

    public function validerLivreurs()
    {
        $livreurs = \App\Models\Livreur::where('statut_validation', 'en_attente')->orWhere('statut_validation', 'refusé')->get();
        return view('admin.livreurs', compact('livreurs'));
    }

    public function changerStatutLivreur(Request $request, $id)
    {
        $livreur = \App\Models\Livreur::findOrFail($id);
        $request->validate([
            'statut_validation' => 'required|in:validé,refusé',
        ]);
        $livreur->statut_validation = $request->input('statut_validation');
        $livreur->save();
        return back()->with('success', 'Statut du livreur mis à jour.');
    }

    public function createBatiment()
    {
        return view('admin.create_batiment');
    }

    public function storeBatiment(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'rue' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:20',
        ]);
        $adresse = \App\Models\Addresse::create([
            'rue' => $data['rue'],
            'ville' => $data['ville'],
            'code_postal' => $data['code_postal'],
        ]);
        \App\Models\Batiment::create([
            'nom' => $data['nom'],
            'id_addresse' => $adresse->id,
        ]);
        return redirect()->route('admin.dashboard')->with('success', 'Bâtiment enregistré !');
    }

    public function batiments()
    {
        $batiments = \App\Models\Batiment::with('addresse')->get();
        return view('admin.batiments', compact('batiments'));
    }

    public function editBatiment($id)
    {
        $batiment = \App\Models\Batiment::with('addresse')->findOrFail($id);
        return view('admin.edit_batiment', compact('batiment'));
    }

    public function updateBatiment(Request $request, $id)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'rue' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:20',
        ]);
        $batiment = \App\Models\Batiment::findOrFail($id);
        $batiment->nom = $data['nom'];
        $batiment->save();
        $batiment->addresse->update([
            'rue' => $data['rue'],
            'ville' => $data['ville'],
            'code_postal' => $data['code_postal'],
        ]);
        return redirect()->route('admin.batiments')->with('success', 'Bâtiment modifié !');
    }

    public function deleteBatiment($id)
    {
        $batiment = \App\Models\Batiment::findOrFail($id);
        $batiment->delete();
        return redirect()->route('admin.batiments')->with('success', 'Bâtiment supprimé !');
    }

    public function updateStatutUtilisateur(Request $request, $id)
    {
        $request->validate([
            'statut_compte' => 'required|in:actif,inactif,suspendu',
        ]);
        $utilisateur = \App\Models\Utilisateur::findOrFail($id);
        $utilisateur->statut_compte = $request->statut_compte;
        $utilisateur->save();
        return back()->with('success', 'Statut du compte mis à jour.');
    }

    public function all(Request $request)
    {
        $query = \App\Models\Utilisateur::query();
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('id_utilisateur', $search)
                  ->orWhere('nom', 'like', "%$search%")
                  ->orWhere('prenom', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }
        $utilisateurs = $query->get();
        $annonces = \App\Models\Annonce::all();
        $paiements = \App\Models\Paiement::all();
        $livraisons = \App\Models\Livraison::all();
        return view('admin.all', compact('utilisateurs', 'annonces', 'paiements', 'livraisons'));
    }

    public function commercants()
    {
        $commercants = \App\Models\Commercant::with('contrats')->get();
        return view('admin.commercants', compact('commercants'));
    }

    public function approuverContrat($id)
    {
        $contrat = \App\Models\Contrat::findOrFail($id);
        $contrat->statut = 'actif';
        $contrat->save();
        return back()->with('success', 'Contrat activé.');
    }
} 