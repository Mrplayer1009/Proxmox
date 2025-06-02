<?php
include 'db.php';

if (isset($_GET['id']) && isset($_GET['user_id'])) {
    $achat_id = intval($_GET['id']);
    $user_id = intval($_GET['user_id']);

    // Récupérer l'achat de l'utilisateur via l'API
    $achat_response = $api_client->get('achats', ['id' => $achat_id, 'user_id' => $user_id]);
    $achat = $achat_response['data'] ?? null;

    // Récupérer les informations de l'utilisateur via l'API
    $user_response = $api_client->get('users', ['id' => $user_id]);
    $utilisateur = $user_response['data'] ?? null;

    if (!$achat || !$utilisateur) {
        die("Achat non trouvé ou utilisateur introuvable.");
    }

    // Génération du PDF
    require('../vendor/setasign/fpdf/fpdf.php');

    class PDF extends FPDF
    {
        function Header()
        {
            $this->Image('../src/fond.jpg', 0, 0, 210, 297);
            $this->SetY(32);
        }

        function Footer()
        {
            $this->SetY(-19);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
        }
    }

    // Créer l'objet PDF
    $pdf = new PDF();
    $pdf->SetMargins(15, 20, 15);
    $pdf->AddPage();
    $pdf->AddFont('Helvetica','','Helvetica.php');
    $pdf->AddFont('Helvetica','B','Helveticab.php');

    // Titre en rouge
    $pdf->SetTextColor(200, 0, 0);
    $pdf->SetFont('Helvetica', 'B', 12);
    $pdf->Cell(0, 10, mb_convert_encoding('Informations', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
    $pdf->Ln(5);

    // Texte en noir
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Helvetica','',12);

    // Infos utilisateur
    $pdf->SetX(25);
    $pdf->Cell(0, 10, mb_convert_encoding('Nom : ' . $utilisateur['nom'], 'ISO-8859-1', 'UTF-8'), 0, 1);
    $pdf->SetX(25);
    $pdf->Cell(0, 10, mb_convert_encoding('Prénom : ' . $utilisateur['prenom'], 'ISO-8859-1', 'UTF-8'), 0, 1);
    $pdf->SetX(25);
    $pdf->Cell(0, 10, mb_convert_encoding('Email : ' . $utilisateur['email'], 'ISO-8859-1', 'UTF-8'), 0, 1);
    $pdf->Ln(5);

    // Contenu de l'achat
    $pdf->SetX(25);
    $pdf->Cell(0, 10, mb_convert_encoding('Commande : ' . $achat['contenu'], 'ISO-8859-1', 'UTF-8'), 0, 1);

    // Affichage pdf
    $pdf->Output('I', 'commande.pdf');
    exit;
} else {
    die("Paramètres manquants. L'ID de l'achat et l'ID de l'utilisateur sont requis.");
}

?>
