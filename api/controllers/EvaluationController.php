<?php
require_once 'models/Evaluation.php';
require_once 'config/database.php';

class EvaluationController {
    private $database;
    private $db;
    private $evaluation;

    public function __construct() {
        $this->database = new Database();
        $this->db = $this->database->connect();
        $this->evaluation = new Evaluation($this->db);
    }

    public function getAll() {
        $stmt = $this->evaluation->readAll();
        $evaluations = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $evaluations[] = $row;
        }

        if(count($evaluations) > 0) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $evaluations
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Aucune évaluation trouvée"
            ]);
        }
    }

    public function getOne($id) {
        if($this->evaluation->read($id)) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => [
                    "id" => $this->evaluation->id,
                    "prestataire_id" => $this->evaluation  => [
                    "id" => $this->evaluation->id,
                    "prestataire_id" => $this->evaluation->prestataire_id,
                    "client_id" => $this->evaluation->client_id,
                    "intervention_id" => $this->evaluation->intervention_id,
                    "note" => $this->evaluation->note,
                    "commentaire" => $this->evaluation->commentaire,
                    "date_evaluation" => $this->evaluation->date_evaluation
                ]
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Évaluation non trouvée"
            ]);
        }
    }

    public function getByPrestataire($prestataire_id) {
        $stmt = $this->evaluation->readByPrestataire($prestataire_id);
        $evaluations = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $evaluations[] = $row;
        }

        if(count($evaluations) > 0) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $evaluations
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Aucune évaluation trouvée pour ce prestataire"
            ]);
        }
    }

    public function getByClient($client_id) {
        $stmt = $this->evaluation->readByClient($client_id);
        $evaluations = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $evaluations[] = $row;
        }

        if(count($evaluations) > 0) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $evaluations
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Aucune évaluation trouvée pour ce client"
            ]);
        }
    }

    public function create($data) {
        if(
            !empty($data->prestataire_id) &&
            !empty($data->client_id) &&
            !empty($data->note)
        ) {
            $this->evaluation->prestataire_id = $data->prestataire_id;
            $this->evaluation->client_id = $data->client_id;
            $this->evaluation->intervention_id = isset($data->intervention_id) ? $data->intervention_id : null;
            $this->evaluation->note = $data->note;
            $this->evaluation->commentaire = isset($data->commentaire) ? $data->commentaire : null;
            
            if($this->evaluation->create()) {
                http_response_code(201);
                echo json_encode([
                    "status" => "success",
                    "message" => "Évaluation créée avec succès"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Impossible de créer l'évaluation"
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Données incomplètes"
            ]);
        }
    }

    public function update($id, $data) {
        $this->evaluation->id = $id;
        
        if($this->evaluation->read($id)) {
            if(isset($data->prestataire_id)) $this->evaluation->prestataire_id = $data->prestataire_id;
            if(isset($data->client_id)) $this->evaluation->client_id = $data->client_id;
            if(isset($data->intervention_id)) $this->evaluation->intervention_id = $data->intervention_id;
            if(isset($data->note)) $this->evaluation->note = $data->note;
            if(isset($data->commentaire)) $this->evaluation->commentaire = $data->commentaire;
            
            if($this->evaluation->update()) {
                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "message" => "Évaluation mise à jour avec succès"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Impossible de mettre à jour l'évaluation"
                ]);
            }
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Évaluation non trouvée"
            ]);
        }
    }

    public function delete($id) {
        $this->evaluation->id = $id;
        
        if($this->evaluation->read($id)) {
            if($this->evaluation->delete()) {
                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "message" => "Évaluation supprimée avec succès"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Impossible de supprimer l'évaluation"
                ]);
            }
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Évaluation non trouvée"
            ]);
        }
    }
}
