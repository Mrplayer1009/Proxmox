<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
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
    
    $value = ($action === 'ban') ? 1 : 0;
    
    // Mettre à jour le statut de bannissement
    $sql = "UPDATE users SET banni = :value WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'value' => $value,
        'id' => $id
    ]);
    
    // Rediriger vers la page principale
    header("Location: backoffice.php");
    exit;
} else {
    die("Paramètres manquants");
}