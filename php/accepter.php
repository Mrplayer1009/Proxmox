<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Accès refusé");
}

include 'db.php';

$id = $_GET['id'] ?? 0;
if ($id > 0) {
    $result = $api_client->put('users', ['is_admin' => 1], ['id' => $id]);
    header('Location: backoffice.php');
    exit;
} else {
    die("ID utilisateur invalide");
}
