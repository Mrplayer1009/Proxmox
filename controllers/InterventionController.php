<?php
require_once 'models/Intervention.php';
require_once 'config/database.php';

class InterventionController {
    private $database;
    private $db;
    private $intervention;

    public function __construct() {
        $this->database = new Database();
        $this->db = $this->database->connect();
        $this->intervention = new Intervention($this->db);
    }

    public function getAll() {
        $stmt = $this->intervention->readAll();
        $interventions = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $interventions[] = $row;
        }

        if(count($interventions) > 0) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $interventions
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Aucune intervention trouvée"
            ]);
        }
    }

    public function getOne($id) {
        if($this->intervention->read($id)) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => [
                    "id" => $this->intervention->id,
                    "prestataire_id" => $this->intervention->prestataire_id,
                    "client_id" => $this->intervention->client_id,
                    "service_id" => $this->intervention->service_id,
                    "date_intervention" => $this->intervention->date_intervention,
                    "heure_debut" => $this->intervention->heure_debut,
                    "heure_fin" => $this->intervention->heure_fin,
                    "statut" => $this->intervention->statut
                ]
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Intervention non trouvée"
            ]);
        }
    }

    public function getByPrestataire($prestataire_id) {
        $stmt = $this->intervention->readByPrestataire($prestataire_id);
        $interventions = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $interventions[] = $row;
        }

        if(count($interventions) > 0) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $interventions
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Aucune intervention trouvée pour ce prestataire"
            ]);
        }
    }

    public function getByClient($client_id) {
        $stmt = $this->intervention->readByClient($client_id);
        $interventions = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $interventions[] = $row;
        }

        if(count($interventions) > 0) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $interventions
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Aucune intervention trouvée pour ce client"
            ]);
        }
    }

    public function getUpcoming($prestataire_id) {
        $stmt = $this->intervention->readUpcoming($prestataire_id);
        $interventions = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $interventions[] = $row;
        }

        if(count($interventions) > 0) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $interventions
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Aucune intervention à venir trouvée"
            ]);
        }
    }

    public function create($data) {
        if(
            !empty($data->prestataire_id) &&
            !empty($data->client_id) &&
            !empty($data->service_id) &&
            !empty($data->date_intervention) &&
            !empty($data->heure_debut) &&
            !empty($data->heure_fin)
        ) {
            $this->intervention->prestataire_id = $data->prestataire_id;
            $this->intervention->client_id = $data->client_id;
            $this->intervention->service_id = $data->service_id;
            $this->intervention->date_intervention = $data->date_intervention;
            $this->intervention->heure_debut = $data->heure_debut;
            $this->intervention->heure_fin = $data->heure_fin;
            $this->intervention->statut = isset($data->statut) ? $data->statut : 'planifiée';
            
            if($this->intervention->create()) {
                http_response_code(201);
                echo json_encode([
                    "status" => "success",
                    "message" => "Intervention créée avec succès"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Impossible de créer l'intervention"
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
        $this->intervention->id = $id;
        
        if($this->intervention->read($id)) {
            if(isset($data->prestataire_id)) $this->intervention->prestataire_id = $data->prestataire_id;
            if(isset($data->client_id)) $this->intervention->client_id = $data->client_id;
            if(isset($data->service_id)) $this->intervention->service_id = $data->service_id;
            if(isset($data->date_intervention)) $this->intervention->date_intervention = $data->date_intervention;
            if(isset($data->heure_debut)) $this->intervention->heure_debut = $data->heure_debut;
            if(isset($data->heure_fin)) $this->intervention->heure_fin = $data->heure_fin;
            if(isset($data->statut)) $this->intervention->statut = $data->statut;
            
            if($this->intervention->update()) {
                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "message" => "Intervention mise à jour avec succès"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Impossible de mettre à jour l'intervention"
                ]);
            }
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Intervention non trouvée"
            ]);
        }
    }

    public function delete($id) {
        $this->intervention->id = $id;
        
        if($this->intervention->read($id)) {
            if($this->intervention->delete()) {
                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "message" => "Intervention supprimée avec succès"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Impossible de supprimer l'intervention"
                ]);
            }
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Intervention non trouvée"
            ]);
        }
    }
}
