<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Récupérer la route demandée
$request = $_GET['route'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

// Router simple
switch ($request) {
    case 'users':
        require_once 'routes/user.php';
        $userRoute = new UserRoute();
        $userRoute->handleRequest($method);
        break;
    case 'roles':
        require_once 'routes/role.php';
        $roleRoute = new RoleRoute();
        $roleRoute->handleRequest($method);
        break;
    case 'interventions':
        require_once 'routes/intervention.php';
        $interventionRoute = new InterventionRoute();
        $interventionRoute->handleRequest($method);
        break;
    case 'services':
        require_once 'routes/service.php';
        $serviceRoute = new ServiceRoute();
        $serviceRoute->handleRequest($method);
        break;
    case 'evaluations':
        require_once 'routes/evaluation.php';
        $evaluationRoute = new EvaluationRoute();
        $evaluationRoute->handleRequest($method);
        break;
    case 'annonces':
        require_once 'routes/annonce.php';
        $annonceRoute = new AnnonceRoute();
        $annonceRoute->handleRequest($method);
        break;
    case 'documents':
        require_once 'routes/document.php';
        $documentRoute = new DocumentRoute();
        $documentRoute->handleRequest($method);
        break;
    case 'achats':
        require_once 'routes/achat.php';
        $achatRoute = new AchatRoute();
        $achatRoute->handleRequest($method);
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Route non trouvée']);
        break;
}
