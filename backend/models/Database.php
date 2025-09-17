<?php
class Database {
    private string $host = "localhost";
    private string $db_name = "parc_national";
    private string $username = "root";
    private string $password = "";    private ?PDO $conn = null;

    public function connect(): PDO {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                    $this->username,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new Exception("Erreur connexion BDD: " . $e->getMessage());
            }
        }
        return $this->conn;
    }
}
