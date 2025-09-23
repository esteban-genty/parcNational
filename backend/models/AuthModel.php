<?php
require_once "Database.php";

class AuthModel extends Database {
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
        // Hash du mot de passe
        $hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

        // Requête SQL préparée
        $sql = "INSERT INTO UTILISATEUR (nom, email, mot_de_passe, role)
                VALUES (:nom, :email, :mot_de_passe, :role)";
        
        $stmt = $this->db->prepare($sql);

        // Exécution avec liaison des paramètres
        return $stmt->execute([
            ":nom"        => $nom,
            ":email"      => $email,
            ":mot_de_passe" => $hash,
            ":role"       => $role
        ]);
    }

    public function login(string $email, string $mot_de_passe): array|false {
        
        $sql = "SELECT * FROM UTILISATEUR WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":email" => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            return $user;
        }
        return false;
    }
}