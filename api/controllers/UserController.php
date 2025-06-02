<?php
require_once 'models/User.php';
require_once 'config/database.php';

class UserController {
    private $database;
    private $db;
    private $user;

    public function __construct() {
        $this->database = new Database();
        $this->db = $this->database->connect();
        $this->user = new User($this->db);
    }

    public function getAll() {
        $stmt = $this->user->readAll();
        $users = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $row;
        }

        if(count($users) > 0) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => $users
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Aucun utilisateur trouvé"
            ]);
        }
    }

    public function getOne($id) {
        if($this->user->read($id)) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data" => [
                    "id" => $this->user->id,
                    "email" => $this->user->email,
                    "is_livreur" => $this->user->is_livreur,
                    "is_prestataire" => $this->user->is_prestataire,
                    "is_admin" => $this->user->is_admin,
                    "nom" => $this->user->nom,
                    "prenom" => $this->user->prenom,
                    "banni" => $this->user->banni
                ]
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Utilisateur non trouvé"
            ]);
        }
    }

    public function create($data) {
        if(empty($data->email) || empty($data->password)) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Données incomplètes. Email et mot de passe sont requis."
            ]);
            return;
        }
        
        // Vérifier si l'email existe déjà
        $this->user->email = $data->email;
        if($this->user->emailExists()) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Cet email est déjà utilisé."
            ]);
            return;
        }
        
        // Assigner les valeurs
        $this->user->email = $data->email;
        $this->user->password = $data->password;
        $this->user->is_livreur = isset($data->is_livreur) ? $data->is_livreur : 0;
        $this->user->is_prestataire = isset($data->is_prestataire) ? $data->is_prestataire : 0;
        $this->user->is_admin = isset($data->is_admin) ? $data->is_admin : 0;
        $this->user->nom = isset($data->nom) ? $data->nom : null;
        $this->user->prenom = isset($data->prenom) ? $data->prenom : null;
        $this->user->banni = isset($data->banni) ? $data->banni : 0;
        
        if($this->user->create()) {
            http_response_code(201);
            echo json_encode([
                "status" => "success",
                "message" => "Utilisateur créé avec succès"
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Impossible de créer l'utilisateur"
            ]);
        }
    }

    public function update($id, $data) {
        $this->user->id = $id;
        
        if($this->user->read($id)) {
            // Vérifier si l'email est modifié et s'il existe déjà
            if(isset($data->email) && $data->email !== $this->user->email) {
                $tempUser = new User($this->db);
                $tempUser->email = $data->email;
                if($tempUser->emailExists()) {
                    http_response_code(400);
                    echo json_encode([
                        "status" => "error",
                        "message" => "Cet email est déjà utilisé."
                    ]);
                    return;
                }
            }
            
            // Mettre à jour les champs
            if(isset($data->email)) $this->user->email = $data->email;
            if(isset($data->password)) $this->user->password = $data->password;
            if(isset($data->is_livreur)) $this->user->is_livreur = $data->is_livreur;
            if(isset($data->is_prestataire)) $this->user->is_prestataire = $data->is_prestataire;
            if(isset($data->is_admin)) $this->user->is_admin = $data->is_admin;
            if(isset($data->nom)) $this->user->nom = $data->nom;
            if(isset($data->prenom)) $this->user->prenom = $data->prenom;
            if(isset($data->banni)) $this->user->banni = $data->banni;
            
            if($this->user->update()) {
                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "message" => "Utilisateur mis à jour avec succès"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Impossible de mettre à jour l'utilisateur"
                ]);
            }
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Utilisateur non trouvé"
            ]);
        }
    }

    public function delete($id) {
        $this->user->id = $id;
        
        if($this->user->read($id)) {
            if($this->user->delete()) {
                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "message" => "Utilisateur supprimé avec succès"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "message" => "Impossible de supprimer l'utilisateur"
                ]);
            }
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Utilisateur non trouvé"
            ]);
        }
    }

    // Méthode pour l'authentification
    public function login($data) {
        if(empty($data->email) || empty($data->password)) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Email et mot de passe requis."
            ]);
            return;
        }
        
        $this->user->email = $data->email;
        $this->user->password = $data->password;
        
        if($this->user->login()) {
            // Créer un token JWT ici si vous implémentez JWT
            
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "message" => "Connexion réussie",
                "data" => [
                    "id" => $this->user->id,
                    "email" => $this->user->email,
                    "is_livreur" => $this->user->is_livreur,
                    "is_prestataire" => $this->user->is_prestataire,
                    "is_admin" => $this->user->is_admin,
                    "nom" => $this->user->nom,
                    "prenom" => $this->user->prenom
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                "status" => "error",
                "message" => "Email ou mot de passe incorrect ou compte banni."
            ]);
        }
    }
}
