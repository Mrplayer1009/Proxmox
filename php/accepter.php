<?php
include 'db.php';
$id = $_GET['id'];
$pdo->query("UPDATE users SET is_admin = 1 WHERE id = $id");
header('Location: backoffice.php');
?>