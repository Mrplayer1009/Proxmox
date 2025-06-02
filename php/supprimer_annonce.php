<?php
session_start();
include 'db.php';

if (!isset($_SESSION['is_livreur']) != 1) {
    header("Location: ../index.php");
    exit();
}

$livreur_id = $_SESSION['user_id'];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $annonce_id = $_GET['id'];

    $check_sql = "SELECT id FROM annonces WHERE id = ? AND livreur_id = ?";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([$annonce_id, $livreur_id]);
    
    if ($check_stmt->rowCount() > 0) {
        $delete_sql = "DELETE FROM annonces WHERE id = ?";
        $delete_stmt = $pdo->prepare($delete_sql);
        if ($delete_stmt->execute([$annonce_id])) {
            $_SESSION['message'] = "Annonce supprimée avec succès.";
        } else {
            $_SESSION['message'] = "Erreur lors de la suppression.";
        }
    } else {
        $_SESSION['message'] = "Vous n'avez pas l'autorisation de supprimer cette annonce.";
    }
}

header("Location: gerer_annonces.php");
exit();
