<?php
/**
 * Configuration de la base de données pour le Parc National des Calanques
 * Utilise PDO pour une connexion sécurisée à MySQL
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'parc_national';
    private $username = 'root';
    private $password = '';
    private $conn;

    /**
     * Établit la connexion à la base de données
     * @return PDO|null
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $exception) {
            error_log("Erreur de connexion: " . $exception->getMessage());
            throw new Exception("Erreur de connexion à la base de données");
        }

        return $this->conn;
    }

    /**
     * Ferme la connexion à la base de données
     */
    public function closeConnection() {
        $this->conn = null;
    }
}
?>
