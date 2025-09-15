
<?php
class Database {
    private $host = "localhost";
    private $db_name = "parc_national";
    private $username = "root"; // change selon ton MySQL
    private $password = ""; // change selon ton MySQL
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            throw new Exception("Erreur connexion BDD: " . $e->getMessage());
        }
        return $this->conn;
    }
}
