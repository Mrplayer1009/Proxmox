<?php
session_start();
// Détruit toutes les variables de session
$_SESSION = array();

// Détruit la session
session_destroy();

// Supprime le cookie de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirige vers la page d'accueil
header("Location: ../index.php");
exit();
?>
