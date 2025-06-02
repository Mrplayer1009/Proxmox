<?php
class User {
    private $conn;
    private $table = "users";

    public $id;
    public $email;
    public $password;
    public $is_livreur;
    public $is_prestataire;
    public $is_admin;
    public $nom;
    public $prenom;
    public $banni;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT id, email, is_livreur, is_prestataire, is_admin, nom, prenom, banni FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read($id) {
        $query = "SELECT id, email, is_livreur, is_prestataire, is_admin, nom, prenom, banni FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->id = $row['id'];
            $this->email = $row['email'];
            $this->is_livreur = $row['is_livreur'];
            $this->is_prestataire = $row['is_prestataire'];
            $this->is_admin = $row['is_admin'];
            $this->nom = $row['nom'];
            $this->prenom = $row['prenom'];
            $this->banni = $row['banni'];
            return true;
        }
        
        return false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (email, password, is_livreur, is_prestataire, is_admin, nom, prenom, banni) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT); // Hashage du mot de passe
        $this->is_livreur = (int)$this->is_livreur;
        $this->is_prestataire = (int)$this->is_prestataire;
        $this->is_admin = (int)$this->is_admin;
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prenom = htmlspecialchars(strip_tags($this->prenom));
        $this->banni = (int)$this->banni;
        
        $stmt->bindParam(1, $this->email);
        $stmt->bindParam(2, $this->password);
        $stmt->bindParam(3, $this->is_livreur);
        $stmt->bindParam(4, $this->is_prestataire);
        $stmt->bindParam(5, $this->is_admin);
        $stmt->bindParam(6, $this->nom);
        $stmt->bindParam(7, $this->prenom);
        $stmt->bindParam(8, $this->banni);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    public function update() {
        // Vérifier si le mot de passe doit être mis à jour
        if(!empty($this->password)) {
            $query = "UPDATE " . $this->table . " 
                      SET email = ?, password = ?, is_livreur = ?, is_prestataire = ?, 
                          is_admin = ?, nom = ?, prenom = ?, banni = ? 
                      WHERE id = ?";
            
            $stmt = $this->conn->prepare($query);
            
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            $this->is_livreur = (int)$this->is_livreur;
            $this->is_prestataire = (int)$this->is_prestataire;
            $this->is_admin = (int)$this->is_admin;
            $this->nom = htmlspecialchars(strip_tags($this->nom));
            $this->prenom = htmlspecialchars(strip_tags($this->prenom));
            $this->banni = (int)$this->banni;
            $this->id = (int)$this->id;
            
            // Lier les paramètres
            $stmt->bindParam(1, $this->email);
            $stmt->bindParam(2, $this->password);
            $stmt->bindParam(3, $this->is_livreur);
            $stmt->bindParam(4, $this->is_prestataire);
            $stmt->bindParam(5, $this->is_admin);
            $stmt->bindParam(6, $this->nom);
            $stmt->bindParam(7, $this->prenom);
            $stmt->bindParam(8, $this->banni);
            $stmt->bindParam(9, $this->id);
        } else {
            // Mise à jour sans changer le mot de passe
            $query = "UPDATE " . $this->table . " 
                      SET email = ?, is_livreur = ?, is_prestataire = ?, 
                          is_admin = ?, nom = ?, prenom = ?, banni = ? 
                      WHERE id = ?";
            
            $stmt = $this->conn->prepare($query);
            
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->is_livreur = (int)$this->is_livreur;
            $this->is_prestataire = (int)$this->is_prestataire;
            $this->is_admin = (int)$this->is_admin;
            $this->nom = htmlspecialchars(strip_tags($this->nom));
            $this->prenom = htmlspecialchars(strip_tags($this->prenom));
            $this->banni = (int)$this->banni;
            $this->id = (int)$this->id;
            
            // Lier les paramètres
            $stmt->bindParam(1, $this->email);
            $stmt->bindParam(2, $this->is_livreur);
            $stmt->bindParam(3, $this->is_prestataire);
            $stmt->bindParam(4, $this->is_admin);
            $stmt->bindParam(5, $this->nom);
            $stmt->bindParam(6, $this->prenom);
            $stmt->bindParam(7, $this->banni);
            $stmt->bindParam(8, $this->id);
        }
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        $this->id = (int)$this->id;
        
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    public function emailExists() {
        $query = "SELECT id, email, password, is_livreur, is_prestataire, is_admin, nom, prenom, banni 
                  FROM " . $this->table . " 
                  WHERE email = ? 
                  LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        
        $this->email = htmlspecialchars(strip_tags($this->email));
        
        $stmt->bindParam(1, $this->email);
        
        $stmt->execute();
        
        $num = $stmt->rowCount();
        
        if($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->id = $row['id'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->is_livreur = $row['is_livreur'];
            $this->is_prestataire = $row['is_prestataire'];
            $this->is_admin = $row['is_admin'];
            $this->nom = $row['nom'];
            $this->prenom = $row['prenom'];
            $this->banni = $row['banni'];
            
            return true;
        }
        
        return false;
    }

    public function login() {
        if($this->emailExists()) {
            if($this->banni == 1) {
                return false;
            }
            
            if(password_verify($this->password, $this->password)) {
                return true;
            }
        }
        
        return false;
    }
}
