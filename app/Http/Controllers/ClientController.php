<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Paiement;
use App\Models\Livraison;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function annonces()
    {
        $annonces = Annonce::where('id_utilisateur', Auth::id())->get();
        return view('client.annonces', compact('annonces'));
    }

    public function createAnnonce()
    {
        return view('client.create_annonce');
    }

    public function storeAnnonce(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'ville_depart' => 'required|string|max:255',
            'poids' => 'required|numeric|min:0',
            'fragile' => 'nullable|boolean',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'date_limite' => 'nullable|date',
            'type_colis' => 'required|in:Alimentaire,Meuble,Colis',
            'nombre' => 'required|integer|min:1',
        ]);
        $data['id_utilisateur'] = Auth::id();
        $data['statut'] = 'en_cours';
        $data['fragile'] = ($request->input('poids', 0) > 0 && $request->has('fragile')) ? 1 : 0;
        if (empty($data['date_limite'])) {
            $data['date_limite'] = null;
        }
        // Les champs non utilisés dans la table sont ignorés automatiquement
        Annonce::create($data);
        return redirect()->route('client.annonces')->with('success', 'Annonce créée !');
    }

    public function acheterAnnonce(Request $request)
    {
        $annonce = \App\Models\Annonce::findOrFail($request->annonce_id);
        if (!$annonce->prix) {
            return back()->with('error', 'Prix non disponible pour cette annonce.');
        }
        return view('annonces.acheter', compact('annonce'));
    }

    public function successAchat(Request $request, $annonce)
    {
        $annonce = \App\Models\Annonce::with('utilisateur')->findOrFail($annonce);
        return view('annonces.success', compact('annonce'));
    }

    public function paiements()
    {
        $paiements = \App\Models\Paiement::where('id_utilisateur', Auth::id())->get();
        return view('client.paiements', compact('paiements'));
    }

    public function dashboard()
    {
        return view('client.dashboard');
    }

    public function annoncesGlobales()
    {
        $annonces = \App\Models\Annonce::with('utilisateur')->where('statut', 'en_cours')->latest()->get();
        return view('annonces.index', compact('annonces'));
    }

    public function afficherAchat($id)
    {
        $annonce = \App\Models\Annonce::findOrFail($id);
        if (!$annonce->prix) {
            return back()->with('error', 'Prix non disponible pour cette annonce.');
        }
        return view('annonces.acheter', compact('annonce'));
    }

    public function pdfAchat($id)
    {
        $annonce = \App\Models\Annonce::with('utilisateur')->findOrFail($id);
        $pdf = \PDF::loadView('annonces.recu_pdf', compact('annonce'));
        return $pdf->download('recu_achat_annonce_'.$annonce->id_annonce.'.pdf');
    }

    public function destroyAnnonce($id)
    {
        $annonce = \App\Models\Annonce::findOrFail($id);
        // Optionnel : vérifier que l'utilisateur est bien le propriétaire
        $annonce->delete();
        return back()->with('success', 'Annonce supprimée.');
    }

    public function changerStock(Request $request, $id)
    {
        $annonce = \App\Models\Annonce::findOrFail($id);
        // Optionnel : vérifier que l'utilisateur est bien le propriétaire
        $nouveauStock = $request->input('nombre', 0);
        $annonce->nombre = $nouveauStock;
        $annonce->statut = ($nouveauStock > 0) ? 'en_cours' : 'terminée';
        $annonce->save();
        return back()->with('success', 'Stock mis à jour.');
    }
} 