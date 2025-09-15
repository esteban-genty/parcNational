<?php
require_once "database.php";

class Auth {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database->connect();
    }

    public function register($nom, $email, $mot_de_passe, $role = "visiteur") {
        $hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);
        $sql = "INSERT INTO UTILISATEUR (nom, email, mot_de_passe, role) 
                VALUES (:nom, :email, :mot_de_passe, :role)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ":nom" => $nom,
            ":email" => $email,
            ":mot_de_passe" => $hash,
            ":role" => $role
        ]);
    }

    public function login($email, $mot_de_passe) {
        $sql = "SELECT * FROM UTILISATEUR WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":email" => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($mot_de_passe, $user["mot_de_passe"])) {
            return true; // ✅ Authentification réussie
        }
        return false; // ❌ Échec
    }
}
