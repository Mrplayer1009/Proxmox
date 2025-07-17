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
        $batiments = \App\Models\Batiment::with('addresse')->get();
        return view('client.create_annonce', compact('batiments'));
    }

    public function storeAnnonce(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'id_addresse' => 'required|numeric',
            'poids' => 'required|numeric|min:0',
            'fragile' => 'nullable|boolean',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'date_limite' => 'nullable|date',
            'type_colis' => 'required|in:Alimentaire,Meuble,Colis',
            'nombre' => 'required|integer|min:1',
        ]);
        $data['id_utilisateur'] = auth()->id();
        $data['statut'] = 'en_cours';
        $data['fragile'] = $request->input('fragile', 0);
        if (empty($data['date_limite'])) {
            $data['date_limite'] = null;
        }
        // On récupère l'id
        $data['id_addresse'] = $request->input('id_addresse');
        //dd($data); // décommenter pour debug si besoin
        \App\Models\Annonce::create($data);
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

    public function mesInterventions()
    {
        $user = auth()->user();
        $reservations = \App\Models\Reservation::where('id_client', $user->id_utilisateur ?? $user->id)->with(['prestation'])->latest()->get();
        return view('client.mes_interventions', compact('reservations'));
    }

    public function annulerReservation($id)
    {
        $reservation = \App\Models\Reservation::findOrFail($id);
        $user = auth()->user();
        if ($reservation->id_client != ($user->id_utilisateur ?? $user->id)) {
            abort(403);
        }
        $reservation->statut = 'annulée';
        $reservation->save();
        return back()->with('success', 'Réservation annulée.');
    }

    public function validerReservation($id)
    {
        $reservation = \App\Models\Reservation::findOrFail($id);
        $user = auth()->user();
        if ($reservation->id_client != ($user->id_utilisateur ?? $user->id)) {
            abort(403);
        }
        $reservation->statut = 'validée';
        $reservation->save();
        return back()->with('success', 'Réservation validée. Vous pouvez maintenant laisser une note.');
    }

    public function noterReservationForm($id)
    {
        $reservation = \App\Models\Reservation::findOrFail($id);
        $user = auth()->user();
        if ($reservation->id_client != ($user->id_utilisateur ?? $user->id) || $reservation->statut !== 'validée') {
            abort(403);
        }
        return view('client.noter_reservation', compact('reservation'));
    }

    public function noterReservation(\Illuminate\Http\Request $request, $id)
    {
        $reservation = \App\Models\Reservation::findOrFail($id);
        $user = auth()->user();
        if ($reservation->id_client != ($user->id_utilisateur ?? $user->id) || $reservation->statut !== 'validée') {
            abort(403);
        }
        $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:500',
        ]);
        $reservation->note = $request->note;
        $reservation->commentaire = $request->commentaire;
        $reservation->save();
        return redirect()->route('client.interventions')->with('success', 'Merci pour votre note !');
    }

    public function profil()
    {
        $user = auth()->user();
        $adresse = null;
        if ($user->adresse) {
            $adresse = \App\Models\Addresse::find($user->adresse);
        }
        return view('client.profil', compact('user', 'adresse'));
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'email' => 'required|email',
            'telephone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'rue' => 'required|string|max:255',
            'ville' => 'required|string|max:100',
            'code_postal' => 'required|string|max:20',
        ]);
        $user->email = $request->email;
        $user->telephone = $request->telephone;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        // Gestion de l'adresse
        if ($user->adresse) {
            $adresse = \App\Models\Addresse::find($user->adresse);
        } else {
            $adresse = new \App\Models\Addresse();
        }
        $adresse->rue = $request->rue;
        $adresse->ville = $request->ville;
        $adresse->code_postal = $request->code_postal;
        $adresse->save();
        // Si l'utilisateur n'avait pas d'adresse, on met à jour la colonne 'adresse'
        if (!$user->adresse) {
            $user->adresse = $adresse->id;
        }
        $user->save();
        return back()->with('success', 'Profil mis à jour !');
    }
} 