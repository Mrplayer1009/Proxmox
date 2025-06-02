<?php
session_start();
if (!isset($_SESSION['type_utilisateur']) || $_SESSION['type_utilisateur'] != 'admin') {
    die("Accès refusé");
}

include 'db.php';

if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = (int)$_GET['id'];
    $type = $_GET['type'];
    
    // Vérifier que le type est valide
    if (!in_array($type, ['admin', 'livreur', 'prestataire', 'commercant', 'client'])) {
        die("Type invalide");
    }
    
    // Mettre à jour le type d'utilisateur via l'API
    $result = $api_client->put('users', [
        'type_utilisateur' => $type
    ], ['id' => $id]);
    
    // Rediriger vers la page principale
    header("Location: backoffice.php");
    exit;
} else {
    die("Paramètres manquants");
}
