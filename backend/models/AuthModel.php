<?php
require_once "Database.php";

class AuthModel extends Database {
    private PDO $db;

    public function __construct() {
        $this->db = $this->connect();
    }

    public function emailExists(string $email): bool {

        // Vérification si l'email existe déjà
    $sql = "SELECT COUNT(*) FROM utilisateur WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":email" => $email]);
        return $stmt->fetchColumn() > 0;
    }


    public function register(
        string $nom, 
        string $email, 
        string $mot_de_passe, 
        string $role = "visiteur"
    ): array|false {
        try {
            if ($this->emailExists($email)) {
                return false;
            }


            $hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

            $sql = "INSERT INTO utilisateur (nom, email, mot_de_passe, role)
                    VALUES (:nom, :email, :mot_de_passe, :role)";
            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                ":nom" => $nom,
                ":email" => $email,
                ":mot_de_passe" => $hash,
                ":role" => $role
            ]);


            $id = $this->db->lastInsertId();
            $sql = "SELECT * FROM utilisateur WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":id" => $id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user ?: false;

        } catch (PDOException $e) {
            return false;
        }
    }


    public function login(string $email, string $mot_de_passe): array|false {
        
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":email" => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            return $user;
        }
        return false;
    }
}