<?php
require_once 'models/Role.php';
require_once 'config/database.php';

class RoleController {
    private $database;
    private $db;
    private $role;

    public function __construct() {
        $this->database = new Database();
        $this->db = $this->database->connect();
        $this->role = new Role($this->db);
    }

    public function getAll() {
        $stmt = $this->role->readAll();
        $roles = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = $row;
        }

        if(count($roles) > 0) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $roles
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Aucun rôle trouvé"
            ]);
        }
    }

    public function getOne($id) {
        if($this->role->read($id)) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => [
                    "id" => $this->role->id,
                    "nom" => $this->role->nom,
                    "description" => $this->role->description,
                    "created_at" => $this->role->created_at
                ]
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Rôle non trouvé"
            ]);
        }
    }

    public function create($data) {
        if(
            !empty($data->nom) &&
            !empty($data->description)
        ) {
            $this->role->nom = $data->nom;
            $this->role->description = $data->description;
            
            if($this->role->create()) {
                http_response_code(201);
                echo json_encode([
                    "status" => "success",
                    "message" => "Rôle créé avec succès"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Impossible de créer le rôle"
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Données incomplètes. Nom et description sont requis."
            ]);
        }
    }

    public function update($id, $data) {
        $this->role->id = $id;
        
        if($this->role->read($id)) {
            if(isset($data->nom)) $this->role->nom = $data->nom;
            if(isset($data->description)) $this->role->description = $data->description;
            
            if($this->role->update()) {
                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "message" => "Rôle mis à jour avec succès"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Impossible de mettre à jour le rôle"
                ]);
            }
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Rôle non trouvé"
            ]);
        }
    }

    public function delete($id) {
        $this->role->id = $id;
        
        if($this->role->read($id)) {
            if($this->role->delete()) {
                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "message" => "Rôle supprimé avec succès"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Impossible de supprimer le rôle"
                ]);
            }
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Rôle non trouvé"
            ]);
        }
    }
}
