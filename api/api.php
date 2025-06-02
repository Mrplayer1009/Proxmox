<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$request_uri = $_SERVER['REQUEST_URI'];
$uri_parts = explode('/', $request_uri);
$endpoint = end($uri_parts);

if (strpos($endpoint, '?') !== false) {
    $endpoint = substr($endpoint, 0, strpos($endpoint, '?'));
}

error_log("API Request: " . $_SERVER['REQUEST_METHOD'] . " " . $request_uri);
error_log("Endpoint detected: " . $endpoint);

switch ($endpoint) {
    case 'users':
        require_once 'routes/user.php';
        $route = new UserRoute();
        $route->handleRequest($_SERVER['REQUEST_METHOD']);
        break;
    case 'roles':
        require_once 'routes/role.php';
        $route = new RoleRoute();
        $route->handleRequest($_SERVER['REQUEST_METHOD']);
        break;
    case 'interventions':
        require_once 'routes/intervention.php';
        $route = new InterventionRoute();
        $route->handleRequest($_SERVER['REQUEST_METHOD']);
        break;
    case 'evaluations':
        require_once 'routes/evaluation.php';
        $route = new EvaluationRoute();
        $route->handleRequest($_SERVER['REQUEST_METHOD']);
        break;
    case 'services':
        require_once 'routes/service.php';
        $route = new ServiceRoute();
        $route->handleRequest($_SERVER['REQUEST_METHOD']);
        break;
    case 'annonces':
        require_once 'routes/annonce.php';
        $route = new AnnonceRoute();
        $route->handleRequest($_SERVER['REQUEST_METHOD']);
        break;
    case 'documents':
        require_once 'routes/document.php';
        $route = new DocumentRoute();
        $route->handleRequest($_SERVER['REQUEST_METHOD']);
        break;
    case 'achats':
        require_once 'routes/achat.php';
        $route = new AchatRoute();
        $route->handleRequest($_SERVER['REQUEST_METHOD']);
        break;
    default:
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "Endpoint not found"
        ]);
        break;
}
?>
