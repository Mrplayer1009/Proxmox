<?php
class Database {
    private $host = "localhost";
    private $db_name = "eco";
    private $username = "root";
    private $password = "root";
    public $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}",
                                   $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo json_encode([
                "status" => "error",
                "message" => "Erreur de connexion : " . $exception->getMessage()
            ]);
            exit;
        }

        return $this->conn;
    }
}
