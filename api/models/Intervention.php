<?php
class Intervention {
    private $conn;
    private $table = "interventions";

    public $id;
    public $prestataire_id;
    public $client_id;
    public $service_id;
    public $date_intervention;
    public $heure_debut;
    public $heure_fin;
    public $statut;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT i.*, s.type_service, 
                         p.nom as prestataire_nom, p.prenom as prestataire_prenom,
                         c.nom as client_nom, c.prenom as client_prenom
                  FROM " . $this->table . " i
                  JOIN services s ON i.service_id = s.id
                  JOIN users p ON i.prestataire_id = p.id
                  JOIN users c ON i.client_id = c.id
                  ORDER BY i.date_intervention DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read($id) {
        $query = "SELECT i.*, s.type_service, 
                         p.nom as prestataire_nom, p.prenom as prestataire_prenom,
                         c.nom as client_nom, c.prenom as client_prenom
                  FROM " . $this->table . " i
                  JOIN services s ON i.service_id = s.id
                  JOIN users p ON i.prestataire_id = p.id
                  JOIN users c ON i.client_id = c.id
                  WHERE i.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->id = $row['id'];
            $this->prestataire_id = $row['prestataire_id'];
            $this->client_id = $row['client_id'];
            $this->service_id = $row['service_id'];
            $this->date_intervention = $row['date_intervention'];
            $this->heure_debut = $row['heure_debut'];
            $this->heure_fin = $row['heure_fin'];
            $this->statut = $row['statut'];
            return true;
        }
        
        return false;
    }

    public function readByPrestataire($prestataire_id) {
        $query = "SELECT i.*, s.type_service, 
                         c.nom as client_nom, c.prenom as client_prenom
                  FROM " . $this->table . " i
                  JOIN services s ON i.service_id = s.id
                  JOIN users c ON i.client_id = c.id
                  WHERE i.prestataire_id = ?
                  ORDER BY i.date_intervention, i.heure_debut";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $prestataire_id);
        $stmt->execute();
        return $stmt;
    }

    public function readByClient($client_id) {
        $query = "SELECT i.*, s.type_service, 
                         p.nom as prestataire_nom, p.prenom as prestataire_prenom
                  FROM " . $this->table . " i
                  JOIN services s ON i.service_id = s.id
                  JOIN users p ON i.prestataire_id = p.id
                  WHERE i.client_id = ?
                  ORDER BY i.date_intervention, i.heure_debut";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->execute();
        return $stmt;
    }

    public function readUpcoming($prestataire_id) {
        $today = date('Y-m-d');
        $three_months_later = date('Y-m-d', strtotime('+3 months'));
        
        $query = "SELECT i.*, s.type_service, 
                         c.nom as client_nom, c.prenom as client_prenom
                  FROM " . $this->table . " i
                  JOIN services s ON i.service_id = s.id
                  JOIN users c ON i.client_id = c.id
                  WHERE i.prestataire_id = ?
                  AND i.date_intervention BETWEEN ? AND ?
                  ORDER BY i.date_intervention, i.heure_debut";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $prestataire_id);
        $stmt->bindParam(2, $today);
        $stmt->bindParam(3, $three_months_later);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (prestataire_id, client_id, service_id, date_intervention, heure_debut, heure_fin, statut) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        // Nettoyer les données
        $this->prestataire_id = htmlspecialchars(strip_tags($this->prestataire_id));
        $this->client_id = htmlspecialchars(strip_tags($this->client_id));
        $this->service_id = htmlspecialchars(strip_tags($this->service_id));
        $this->date_intervention = htmlspecialchars(strip_tags($this->date_intervention));
        $this->heure_debut = htmlspecialchars(strip_tags($this->heure_debut));
        $this->heure_fin = htmlspecialchars(strip_tags($this->heure_fin));
        $this->statut = htmlspecialchars(strip_tags($this->statut));
        
        // Lier les paramètres
        $stmt->bindParam(1, $this->prestataire_id);
        $stmt->bindParam(2, $this->client_id);
        $stmt->bindParam(3, $this->service_id);
        $stmt->bindParam(4, $this->date_intervention);
        $stmt->bindParam(5, $this->heure_debut);
        $stmt->bindParam(6, $this->heure_fin);
        $stmt->bindParam(7, $this->statut);
        
        // Exécuter la requête
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET prestataire_id = ?, client_id = ?, service_id = ?, 
                      date_intervention = ?, heure_debut = ?, heure_fin = ?, statut = ? 
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        // Nettoyer les données
        $this->prestataire_id = htmlspecialchars(strip_tags($this->prestataire_id));
        $this->client_id = htmlspecialchars(strip_tags($this->client_id));
        $this->service_id = htmlspecialchars(strip_tags($this->service_id));
        $this->date_intervention = htmlspecialchars(strip_tags($this->date_intervention));
        $this->heure_debut = htmlspecialchars(strip_tags($this->heure_debut));
        $this->heure_fin = htmlspecialchars(strip_tags($this->heure_fin));
        $this->statut = htmlspecialchars(strip_tags($this->statut));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Lier les paramètres
        $stmt->bindParam(1, $this->prestataire_id);
        $stmt->bindParam(2, $this->client_id);
        $stmt->bindParam(3, $this->service_id);
        $stmt->bindParam(4, $this->date_intervention);
        $stmt->bindParam(5, $this->heure_debut);
        $stmt->bindParam(6, $this->heure_fin);
        $stmt->bindParam(7, $this->statut);
        $stmt->bindParam(8, $this->id);
        
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
