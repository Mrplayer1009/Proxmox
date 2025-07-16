<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class PrestataireController extends Controller
{
    public function dashboard()
    {
        $userId = \Auth::id();
        $prestataire = \App\Models\Prestataire::where('id_utilisateur', $userId)->first();
        $noteMoyenne = null;
        if ($prestataire) {
            $noteMoyenne = \App\Models\Reservation::where('id_prestataire', $prestataire->id_prestataire)
                ->whereNotNull('note')
                ->avg('note');
        }
        return view('prestataire.dashboard', compact('prestataire', 'noteMoyenne'));
    }

    public function showInscription()
    {
        return view('prestataire.inscription');
    }

    public function storeInscription(Request $request)
    {
        $request->validate([
            'nom_entreprise' => 'required|string|max:255',
            'siret' => 'required|string|max:20',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
        ]);
        $userId = Auth::id();
        // Vérifier si déjà prestataire
        if (\App\Models\Prestataire::where('id_utilisateur', $userId)->exists()) {
            return redirect()->route('prestataire.dashboard')->with('error', 'Vous êtes déjà inscrit comme prestataire.');
        }
        \App\Models\Prestataire::create([
            'id_utilisateur' => $userId,
            'nom_entreprise' => $request->nom_entreprise,
            'siret' => $request->siret,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
        ]);
        return redirect()->route('prestataire.dashboard')->with('success', 'Inscription enregistrée. En attente de validation.');
    }

    public function calendrier()
    {
        $userId = Auth::id();
        $prestataire = \App\Models\Prestataire::where('id_utilisateur', $userId)->first();
        $interventions = collect();
        if ($prestataire) {
            $prestations = \App\Models\Prestation::where('id_prestataire', $prestataire->id_prestataire)->pluck('id_prestation');
            $interventions = \App\Models\Reservation::whereIn('id_prestation', $prestations)->get();
        }
        return view('prestataire.calendrier', compact('interventions'));
    }

    public function interventions()
    {
        $userId = auth()->id();
        $prestataire = \App\Models\Prestataire::where('id_utilisateur', $userId)->first();
        $interventions = collect();
        if ($prestataire) {
            $interventions = \App\Models\Reservation::where('id_prestataire', $prestataire->id_prestataire)
                ->orderBy('date', 'desc')
                ->get();
        }
        return view('prestataire.interventions', compact('interventions'));
    }

    public function factures()
    {
        $userId = Auth::id();
        $prestataire = \App\Models\Prestataire::where('id_utilisateur', $userId)->first();
        $factures = collect();
        if ($prestataire) {
            $factures = \App\Models\Facture::where('id_prestataire', $prestataire->id_prestataire)->orderBy('date_generation', 'desc')->get();
        }
        return view('prestataire.factures', compact('factures'));
    }

    public function facturePdf($id)
    {
        $facture = \App\Models\Facture::with('prestataire')->findOrFail($id);
        $pdf = \PDF::loadView('pdf.facture', compact('facture'));
        $filename = 'facture_'.$facture->mois.'.pdf';
        return $pdf->download($filename);
    }

    public function annulerIntervention($id)
    {
        $userId = Auth::id();
        $prestataire = \App\Models\Prestataire::where('id_utilisateur', $userId)->first();
        if (!$prestataire) {
            return redirect()->back()->with('error', 'Accès refusé.');
        }
        $intervention = \App\Models\Reservation::findOrFail($id);
        // checking de l'intervention si = prestataire
        $prestation = \App\Models\Prestation::find($intervention->id_prestation);
        if (!$prestation || $prestation->id_prestataire != $prestataire->id_prestataire) {
            return redirect()->back()->with('error', 'Accès refusé.');
        }
        if ($intervention->statut !== 'annulée') {
            $intervention->statut = 'annulée';
            $intervention->save();
            return redirect()->back()->with('success', 'Intervention annulée.');
        }
        return redirect()->back()->with('info', 'Intervention déjà annulée.');
    }

    public function uploadPiece(Request $request)
    {
        $user = auth()->user();
        $prestataire = \App\Models\Prestataire::where('id_utilisateur', $user->id_utilisateur ?? $user->id)->first();
        if (!$prestataire) {
            return back()->with('error', 'Prestataire introuvable.');
        }
        $request->validate([
            'piece' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);
        $path = $request->file('piece')->store('pieces_prestataire', 'public');
        $prestataire->piece_justificative = $path;
        $prestataire->statut_validation = 'en_attente';
        $prestataire->save();
        return back()->with('success', 'Pièce justificative envoyée. En attente de validation.');
    }
} 