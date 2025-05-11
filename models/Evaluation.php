<?php
class Evaluation {
    private $conn;
    private $table = "evaluations";

    public $id;
    public $prestataire_id;
    public $client_id;
    public $intervention_id;
    public $note;
    public $commentaire;
    public $date_evaluation;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT e.*, 
                         p.nom as prestataire_nom, p.prenom as prestataire_prenom,
                         c.nom as client_nom, c.prenom as client_prenom,
                         i.date_intervention, s.type_service
                  FROM " . $this->table . " e
                  JOIN users p ON e.prestataire_id = p.id
                  JOIN users c ON e.client_id = c.id
                  LEFT JOIN interventions i ON e.intervention_id = i.id
                  LEFT JOIN services s ON i.service_id = s.id
                  ORDER BY e.date_evaluation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read($id) {
        $query = "SELECT e.*, 
                         p.nom as prestataire_nom, p.prenom as prestataire_prenom,
                         c.nom as client_nom, c.prenom as client_prenom,
                         i.date_intervention, s.type_service
                  FROM " . $this->table . " e
                  JOIN users p ON e.prestataire_id = p.id
                  JOIN users c ON e.client_id = c.id
                  LEFT JOIN interventions i ON e.intervention_id = i.id
                  LEFT JOIN services s ON i.service_id = s.id
                  WHERE e.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->id = $row['id'];
            $this->prestataire_id = $row['prestataire_id'];
            $this->client_id = $row['client_id'];
            $this->intervention_id = $row['intervention_id'];
            $this->note = $row['note'];
            $this->commentaire = $row['commentaire'];
            $this->date_evaluation = $row['date_evaluation'];
            return true;
        }
        
        return false;
    }

    public function readByPrestataire($prestataire_id) {
        $query = "SELECT e.*, 
                         c.nom as client_nom, c.prenom as client_prenom,
                         i.date_intervention, s.type_service
                  FROM " . $this->table . " e
                  JOIN users c ON e.client_id = c.id
                  LEFT JOIN interventions i ON e.intervention_id = i.id
                  LEFT JOIN services s ON i.service_id = s.id
                  WHERE e.prestataire_id = ?
                  ORDER BY e.date_evaluation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $prestataire_id);
        $stmt->execute();
        return $stmt;
    }

    public function readByClient($client_id) {
        $query = "SELECT e.*, 
                         p.nom as prestataire_nom, p.prenom as prestataire_prenom,
                         i.date_intervention, s.type_service
                  FROM " . $this->table . " e
                  JOIN users p ON e.prestataire_id = p.id
                  LEFT JOIN interventions i ON e.intervention_id = i.id
                  LEFT JOIN services s ON i.service_id = s.id
                  WHERE e.client_id = ?
                  ORDER BY e.date_evaluation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (prestataire_id, client_id, intervention_id, note, commentaire) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        // Nettoyer les données
        $this->prestataire_id = htmlspecialchars(strip_tags($this->prestataire_id));
        $this->client_id = htmlspecialchars(strip_tags($this->client_id));
        $this->intervention_id = $this->intervention_id ? htmlspecialchars(strip_tags($this->intervention_id)) : null;
        $this->note = htmlspecialchars(strip_tags($this->note));
        $this->commentaire = $this->commentaire ? htmlspecialchars(strip_tags($this->commentaire)) : null;
        
        // Lier les paramètres
        $stmt->bindParam(1, $this->prestataire_id);
        $stmt->bindParam(2, $this->client_id);
        $stmt->bindParam(3, $this->intervention_id);
        $stmt->bindParam(4, $this->note);
        $stmt->bindParam(5, $this->commentaire);
        
        // Exécuter la requête
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET prestataire_id = ?, client_id = ?, intervention_id = ?, 
                      note = ?, commentaire = ? 
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        // Nettoyer les données
        $this->prestataire_id = htmlspecialchars(strip_tags($this->prestataire_id));
        $this->client_id = htmlspecialchars(strip_tags($this->client_id));
        $this->intervention_id = $this->intervention_id ? htmlspecialchars(strip_tags($this->intervention_id)) : null;
        $this->note = htmlspecialchars(strip_tags($this->note));
        $this->commentaire = $this->commentaire ? htmlspecialchars(strip_tags($this->commentaire)) : null;
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Lier les paramètres
        $stmt->bindParam(1, $this->prestataire_id);
        $stmt->bindParam(2, $this->client_id);
        $stmt->bindParam(3, $this->intervention_id);
        $stmt->bindParam(4, $this->note);
        $stmt->bindParam(5, $this->commentaire);
        $stmt->bindParam(6, $this->id);
        
        // Exécuter la requête
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        // Nettoyer l'ID
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Lier l'ID
        $stmt->bindParam(1, $this->id);
        
        // Exécuter la requête
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}
