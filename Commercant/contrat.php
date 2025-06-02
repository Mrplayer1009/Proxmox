<?php
session_start();
include '../php/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['type_utilisateur'] !== 'commercant') {
    header('Location: ../php/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$commercant_response = $api_client->get('commercants', ['id_utilisateur' => $user_id]);
$commercant = $commercant_response['data'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_contract'])) {
    $errors = [];
    
    if (!isset($_FILES['contract_file']) || $_FILES['contract_file']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Veuillez sélectionner un fichier à téléverser.";
    } else {
        $allowed_types = ['application/pdf'];
        $file_type = $_FILES['contract_file']['type'];
    // Je dois le finir
