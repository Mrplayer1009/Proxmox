<?php
require_once 'controllers/EvaluationController.php';

class EvaluationRoute {
    private $controller;

    public function __construct() {
        $this->controller = new EvaluationController();
    }

    public function handleRequest($method) {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $prestataire_id = isset($_GET['prestataire_id']) ? $_GET['prestataire_id'] : null;
        $client_id = isset($_GET['client_id']) ? $_GET['client_id'] : null;
        
        switch ($method) {
            case 'GET':
                if ($id) {
                    $this->controller->getOne($id);
                } elseif ($prestataire_id) {
                    $this->controller->getByPrestataire($prestataire_id);
                } elseif ($client_id) {
                    $this->controller->getByClient($client_id);
                } else {
                    $this->controller->getAll();
                }
                break;
            case 'POST':
                $data = json_decode(file_get_contents("php://input"));
                $this->controller->create($data);
                break;
            case 'PUT':
                if ($id) {
                    $data = json_decode(file_get_contents("php://input"));
                    $this->controller->update($id, $data);
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID requis pour la mise à jour']);
                }
                break;
            case 'DELETE':
                if ($id) {
                    $this->controller->delete($id);
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID requis pour la suppression']);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Méthode non autorisée']);
                break;
        }
    }
}
