<?php
require_once 'controllers/AchatController.php';

class AchatRoute {
    private $controller;

    public function __construct() {
        $this->controller = new AchatController();
    }

    public function handleRequest($method) {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
        $action = isset($_GET['action']) ? $_GET['action'] : null;
        
        switch ($method) {
            case 'GET':
                if ($id && $user_id && $action === 'pdf') {
                    $this->controller->generatePdf($id, $user_id);
                } elseif ($id) {
                    $this->controller->getOne($id);
                } elseif ($user_id) {
                    $this->controller->getByUser($user_id);
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
