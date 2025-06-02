<?php
session_start();
if (!isset($_SESSION['type_utilisateur']) || $_SESSION['type_utilisateur'] != 'admin') {
    die("Accès refusé");
}

include 'db.php';

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    // Vérifier que l'action est valide
    if ($action !== 'ban' && $action !== 'unban') {
        die("Action invalide");
    }
    
    $statut = ($action === 'ban') ? 'banni' : 'actif';
    
    // Mettre à jour le statut de bannissement
    $result = $api_client->put('users', [
        'statut_compte' => $statut
    ], ['id' => $id]);
    
    // Rediriger vers la page principale
    header("Location: backoffice.php");
    exit;
} else {
    die("Paramètres manquants");
}
