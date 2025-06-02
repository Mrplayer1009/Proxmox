<?php
<<<<<<< HEAD

require_once __DIR__  . "/librairies/method.php";
require_once __DIR__  . "/librairies/path.php";
require_once __DIR__  . "/librairies/response.php";

require_once __DIR__ . "/routes/books/getAll.php";
require_once __DIR__ . "/routes/books/getAllp.php";
require_once __DIR__ . "/routes/books/getOne.php";
require_once __DIR__ . "/routes/books/getOnep.php";
require_once __DIR__ . "/routes/books/getRes.php";
require_once __DIR__ . "/routes/books/create.php";
require_once __DIR__ . "/routes/books/update.php";
require_once __DIR__ . "/routes/books/delete.php";
require_once __DIR__ . "/routes/books/deletep.php";
require_once __DIR__ . "/routes/books/deleter.php";

require_once __DIR__ . "/repositories/booksRepository.php";




if(isPath("doctors")) {
    if(isMethod("GET")) {
        GAUser();
        die();
    } 

    if(isMethod("POST")) {
        $entityBody = json_decode(file_get_contents('php://input'), true);
        createBook($entityBody);
        die();
    }  
} 

if(isPath("patients")) {
    if(isMethod("GET")) {
        getAllPatients();
        die();
    } 
}

if(isPath("doctors/:id") &&(isMethod("GET"))) {
        getOneBook();
        die();
    echo "methode non fonctionnel";
    die();
    
}

if(isPath("patients/:id") &&(isMethod("GET"))) {
    getOnePatient();
    die();
echo "methode non fonctionnel";
die();
}

if (isPath("patients/:id/reservations")&& (isMethod("GET")) ) {
    getRes();
    echo "réussite";
    die();
    
}

if (isPath("doctors/:id")&& (isMethod("PUT"))) {
    updateBook();
    die();
echo "livre pas modifier";
die();
}

if (isPath("doctors/:id") && isMethod("DELETE")) {
    deleteBook();
    die();
}

if (isPath("patients/:id") && isMethod("DELETE")) {
    deletePatient();
    die();
}

if (isPath("reservations/:id") && isMethod("DELETE")) {
    deleteRes();
    die();
}

echo "chemin inconnu";

=======
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
>>>>>>> c69415ef13d20049e4b6680fe0c0afd0724e2ce4
