<?php
require_once "Database.php";

class Singin extends Database {
    private PDO $db;

    public function __construct() {
        $this->db = $this->connect();
    }

    public function register(
        string $nom, 
        string $email, 
        string $mot_de_passe, 
        string $role = "visiteur"
    ): bool {
        
    $hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

    $sql = "INSERT INTO UTILISATEUR (nom, email, mot_de_passe, role)
            VALUES (:nom, :email, :mot_de_passe, :role)";
    
    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        ":nom"        => $nom,
        ":email"      => $email,
        ":mot_de_passe" => $hash,
        ":role"       => $role
    ]);
}


}
