<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Paiement;
use App\Models\Livraison;

class PdfController extends Controller
{
    public function paiement($id)
    {
        $paiement = Paiement::with('utilisateur')->findOrFail($id);
        $data = [
            'title' => 'Reçu de paiement',
            'paiement' => $paiement,
        ];
        $pdf = Pdf::loadView('pdf.paiement', $data);
        return $pdf->download('paiement_'.$id.'.pdf');
    }

    public function livraison($id)
    {
        $livraison = Livraison::with(['annonce','livreur'])->findOrFail($id);
        $data = [
            'title' => 'Bon de livraison',
            'livraison' => $livraison,
        ];
        $pdf = Pdf::loadView('pdf.livraison', $data);
        return $pdf->download('livraison_'.$id.'.pdf');
    }
} 