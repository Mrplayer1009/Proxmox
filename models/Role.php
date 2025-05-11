<?php
class Role {
    private $conn;
    private $table = "roles";

    public $id;
    public $nom;
    public $description;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT id, nom, description, created_at FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read($id) {
        $query = "SELECT id, nom, description, created_at FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->id = $row['id'];
            $this->nom = $row['nom'];
            $this->description = $row['description'];
            $this->created_at = $row['created_at'];
            return true;
        }
        
        return false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " (nom, description) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        
        // Nettoyer les données
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->description = htmlspecialchars(strip_tags($this->description));
        
        // Lier les paramètres
        $stmt->bindParam(1, $this->nom);
        $stmt->bindParam(2, $this->description);
        
        // Exécuter la requête
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET nom = ?, description = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        // Nettoyer les données
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Lier les paramètres
        $stmt->bindParam(1, $this->nom);
        $stmt->bindParam(2, $this->description);
        $stmt->bindParam(3, $this->id);
        
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
