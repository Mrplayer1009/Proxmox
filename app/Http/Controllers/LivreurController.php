<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livraison;
use App\Models\Localisation;

class LivreurController extends Controller
{
    /**
     * Display the livreur dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $user = auth()->user();
        $livreur = \App\Models\Livreur::where('id_utilisateur', $user->id_utilisateur ?? $user->id)->first();
        if (!$livreur || !$livreur->pieces_justificatives || $livreur->statut_validation !== 'validé') {
            return view('livreur.upload_piece', compact('livreur'));
        }
        return view('livreur.dashboard', compact('livreur'));
    }

    /**
     * Display the livreur services page.
     *
     * @return \Illuminate\View\View
     */
    public function services()
    {
        return view('livreur.services');
    }

    /**
     * Show form to create a new annonce.
     *
     * @return \Illuminate\View\View
     */
    public function createAnnonce()
    {
        return view('livreur.annonces.create');
    }

    /**
     * Store a new annonce.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAnnonce(Request $request)
    {
        $userId = auth()->id();

        $request->validate([
            'type_annonce' => 'required|string|max:50',
            'role_emetteur' => 'required|string|max:50',
            'description' => 'required|string',
            'lieu_depart' => 'required|string|max:255',
            'date_souhaitee' => 'required|date',
        ]);

        \App\Models\Annonce::create([
            'id_utilisateur' => $userId,
            'type_annonce' => $request->input('type_annonce'),
            'role_emetteur' => $request->input('role_emetteur'),
            'description' => $request->input('description'),
            'lieu_depart' => $request->input('lieu_depart'),
            'date_souhaitee' => $request->input('date_souhaitee'),
            'statut' => 'active',
        ]);

        return redirect()->route('livreur.dashboard')->with('success', 'Annonce créée avec succès.');
    }

    /**
     * List deliveries assigned to the authenticated livreur.
     *
     * @return \Illuminate\View\View
     */
    public function deliveries()
    {
        $user = auth()->user();
        $livreurId = $user->id_livreur ?? \App\Models\Livreur::where('id_utilisateur', $user->id_utilisateur ?? $user->id)->value('id_livreur');
        $deliveries = \App\Models\Livraison::where('id_livreur', $livreurId)->get();
        return view('livreur.deliveries.index', compact('deliveries'));
    }

    /**
     * Show a specific delivery and its locations.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showDelivery($id)
    {
        $delivery = \App\Models\Livraison::with(['localisations', 'adresseArrivee'])->findOrFail($id);
        $batiments = \App\Models\Batiment::with('addresse')->get();
        return view('livreur.deliveries.show', compact('delivery', 'batiments'));
    }

    /**
     * Update delivery locations for a specific delivery.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLocations(Request $request, $id)
    {
        $delivery = \App\Models\Livraison::findOrFail($id);
        $locations = $request->input('locations', []);
        // Suppression des anciennes localisations
        $delivery->localisations()->delete();
        // Calcul des ordres disponibles
        $usedOrders = [];
        $toInsert = [];
        foreach ($locations as $lieu) {
            if (trim($lieu) !== '') {
                $toInsert[] = $lieu;
            }
        }
        for ($i = 0; $i < count($toInsert); $i++) {
            // Cherche le plus petit ordre disponible
            $ordre = 0;
            while (in_array($ordre, $usedOrders)) {
                $ordre++;
            }
            $delivery->localisations()->create([
                'nom' => $toInsert[$i],
                'ordre' => $ordre,
                'livraison_id' => $delivery->id,
                'cree_le' => now(),
                'modifie_le' => now(),
            ]);
            $usedOrders[] = $ordre;
        }
        // Mise à jour du lieu_actuel avec la dernière destination saisie
        if (!empty($toInsert)) {
            $delivery->lieu_actuel = end($toInsert);
            $delivery->save();
        }
        return back()->with('success', 'Lieux de livraison mis à jour.');
    }

    /**
     * Display planning for the authenticated livreur.
     *
     * @return \Illuminate\View\View
     */
    public function planning()
    {
        $livreurId = auth()->id();
        $planning = \App\Models\Planning::where('id_livreur', $livreurId)->orderBy('date')->get();

        return view('livreur.planning.index', compact('planning'));
    }

    /**
     * Show form to create a new planning entry.
     *
     * @return \Illuminate\View\View
     */
    public function createPlanning()
    {
        return view('livreur.planning.create');
    }

    /**
     * Store a new planning entry.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePlanning(Request $request)
    {
        $livreurId = auth()->id();

        $request->validate([
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        \App\Models\Planning::create([
            'id_livreur' => $livreurId,
            'date' => $request->input('date'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('livreur.planning.index')->with('success', 'Planning ajouté avec succès.');
    }

    public function mesLivraisons()
    {
        $user = auth()->user();
        try {
            $livraisons = \App\Models\Livraison::where('id_utilisateur', $user->id_utilisateur ?? $user->id)
                ->whereIn('statut', ['en_cours', 'en_attente'])
                ->get();
        } catch (\Exception $e) {
            $livraisons = collect(); 
        }
        return view('livraisons.index', compact('livraisons'));
    }

    public function uploadPiece(Request $request)
    {
        $user = auth()->user();
        $livreur = \App\Models\Livreur::where('id_utilisateur', $user->id_utilisateur ?? $user->id)->first();
        if (!$livreur) {
            $livreur = \App\Models\Livreur::create([
                'id_utilisateur' => $user->id_utilisateur ?? $user->id,
                'statut_validation' => 'en_attente',
            ]);
        }
        $request->validate([
            'piece' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);
        $path = $request->file('piece')->store('pieces_livreur', 'public');
        $livreur->pieces_justificatives = $path;
        $livreur->statut_validation = 'en_attente';
        $livreur->save();
        return back()->with('success', 'Pièce justificative envoyée. En attente de validation.');
    }

    public function marquerLivree(Request $request, $id)
    {
        $request->validate([
            'code_validation' => 'required|string'
        ]);
        $livraison = \App\Models\Livraison::findOrFail($id);
        if ($livraison->code_validation !== $request->input('code_validation')) {
            return back()->with('error', 'Code de livraison incorrect.');
        }
        $livraison->statut = 'livrée';
        $livraison->id_livreur = null;
        $livraison->save();
        return redirect()->back()->with('success', 'Livraison marquée comme livrée.');
    }

    public function addLocation(Request $request, $id_livraison)
    {
        $livraison = \App\Models\Livraison::findOrFail($id_livraison);
        $request->validate([
            'lieu' => 'required|string|max:255',
        ]);
        $maxOrdre = $livraison->localisations()->max('ordre');
        $ordre = is_null($maxOrdre) ? 0 : $maxOrdre + 1;
        $livraison->localisations()->create([
            'nom' => $request->input('lieu'),
            'ordre' => $ordre,
            'livraison_id' => $livraison->id,
            'cree_le' => now(),
            'modifie_le' => now(),
        ]);

        // Mettre à jour le statut et le livreur
        $livraison->statut = 'en_attente';
        $livraison->id_livreur = null;
        $livraison->save();

        return redirect()->route('livreur.deliveries')
            ->with('success', 'Localisation ajoutée, livraison remise en attente.');
    }

    public function paiementsLivreur()
    {
        $user = auth()->user();
        $livreur = \App\Models\Livreur::where('id_utilisateur', $user->id_utilisateur ?? $user->id)->first();
        $paiements = $livreur ? \App\Models\PaiementLivreur::where('id_livreur', $livreur->id_livreur)->orderByDesc('date_paiement')->get() : collect();
        return view('livreur.paiements', compact('paiements'));
    }

    public function paiementsPdf()
    {
        $user = auth()->user();
        $livreur = \App\Models\Livreur::where('id_utilisateur', $user->id_utilisateur ?? $user->id)->first();
        $paiements = $livreur ? \App\Models\PaiementLivreur::where('id_livreur', $livreur->id_livreur)->orderByDesc('date_paiement')->get() : collect();
        $pdf = \PDF::loadView('livreur.paiements_pdf', compact('paiements', 'livreur'));
        return $pdf->download('paiements_livreur.pdf');
    }

    public function paiementPdf($id)
    {
        $user = auth()->user();
        $livreur = \App\Models\Livreur::where('id_utilisateur', $user->id_utilisateur ?? $user->id)->first();
        $paiement = $livreur ? \App\Models\PaiementLivreur::where('id_livreur', $livreur->id_livreur)->where('id_paiement', $id)->firstOrFail() : null;
        $pdf = \PDF::loadView('livreur.paiement_pdf', compact('paiement', 'livreur'));
        return $pdf->download('paiement_'.$id.'.pdf');
    }

    public function createDeplacement()
    {
        $batiments = \App\Models\Batiment::with('addresse')->get();
        return view('livreur.create_deplacement', compact('batiments'));
    }

    public function storeDeplacement(Request $request)
    {
        $user = auth()->user();
        $livreur = \App\Models\Livreur::where('id_utilisateur', $user->id_utilisateur ?? $user->id)->first();
        $request->validate([
            'date' => 'required|date',
            'lieu_arrivee' => 'required|integer|exists:addresse,id',
            'description' => 'nullable|string|max:255',
        ]);
        \App\Models\Planning::create([
            'id_livreur' => $livreur->id_livreur,
            'date' => $request->input('date'),
            'lieu_arrivee' => $request->input('lieu_arrivee'), // id de l'adresse
            'description' => $request->input('description'),
        ]);
        return redirect()->route('livreur.deplacements')->with('success', 'Déplacement ajouté !');
    }

    public function editDeplacement($id)
    {
        $deplacement = \App\Models\Planning::findOrFail($id);
        $batiments = \App\Models\Batiment::with('addresse')->get();
        return view('livreur.edit_deplacement', compact('deplacement', 'batiments'));
    }

    public function updateDeplacement(Request $request, $id)
    {
        $deplacement = \App\Models\Planning::findOrFail($id);
        $request->validate([
            'date' => 'required|date',
            'lieu_arrivee' => 'required|integer|exists:addresse,id',
            'description' => 'nullable|string|max:255',
        ]);
        $deplacement->update([
            'date' => $request->input('date'),
            'lieu_arrivee' => $request->input('lieu_arrivee'),
            'description' => $request->input('description'),
        ]);
        return redirect()->route('livreur.deplacements')->with('success', 'Déplacement modifié !');
    }

    public function deleteDeplacement($id)
    {
        $deplacement = \App\Models\Planning::findOrFail($id);
        $deplacement->delete();
        return redirect()->route('livreur.deplacements')->with('success', 'Déplacement supprimé !');
    }

    public function prendreLivraisons()
    {
        $livraisons = \App\Models\Livraison::where('statut', 'en_attente')->get();
        return view('livreur.deliveries.prendre', compact('livraisons'));
    }

    public function prendreLivraison($id)
    {
        $livraison = \App\Models\Livraison::findOrFail($id);
        $user = auth()->user();

        if ($livraison->statut !== 'en_attente') {
            return back()->with('error', 'Cette livraison n\'est plus disponible.');
        }

        $livreur = \App\Models\Livreur::where('id_utilisateur', $user->id_utilisateur ?? $user->id)->first();
        $livraison->id_livreur = $livreur ? $livreur->id_livreur : null;
        $livraison->statut = 'en_cours';
        $livraison->save();

        return redirect()->route('livreur.deliveries')->with('success', 'Livraison prise avec succès !');
    }

    public function deplacements()
    {
        $user = auth()->user();
        $livreur = \App\Models\Livreur::where('id_utilisateur', $user->id_utilisateur ?? $user->id)->first();
        $deplacements = \App\Models\Planning::where('id_livreur', $livreur->id_livreur)->get();
        $adresses = \App\Models\Addresse::whereIn('id', $deplacements->pluck('lieu_arrivee'))->get()->keyBy('id');
        return view('livreur.deplacements', compact('deplacements', 'adresses'));
    }

    public function livraisonsPourDeplacement($id)
    {
        $deplacement = \App\Models\Planning::findOrFail($id);
        $livraisons = \App\Models\Livraison::with('adresseArrivee')->where('id_adresse_arrivee', $deplacement->lieu_arrivee)->get();
        return view('livreur.livraisons_pour_deplacement', compact('livraisons', 'deplacement'));
    }
}
