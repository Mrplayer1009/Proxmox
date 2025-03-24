<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Accès refusé");
}

include 'db.php';

if (isset($_GET['id']) && isset($_GET['type']) && isset($_GET['action'])) {
    $id = (int)$_GET['id'];
    $type = $_GET['type'];
    $action = $_GET['action'];
    
    // Vérifier que le type est valide
    if ($type !== 'livreur' && $type !== 'prestataire') {
        die("Type invalide");
    }
    
    // Vérifier que l'action est valide
    if ($action !== 'add' && $action !== 'remove') {
        die("Action invalide");
    }
    
    $column = "is_" . $type;
    $value = ($action === 'add') ? 1 : 0;
    
    // Mettre à jour le statut
    $sql = "UPDATE users SET $column = :value WHERE id = :id";
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