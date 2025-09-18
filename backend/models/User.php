<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Modèle User pour la gestion des utilisateurs du Parc National des Calanques
 */
class User {
    private $conn;
    private $table_name = "utilisateur";

    // Propriétés de l'utilisateur
    public $id;
    public $nom;
    public $email;
    public $mot_de_passe;
    public $role;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Crée un nouvel utilisateur
     * @return bool
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nom=:nom, email=:email, mot_de_passe=:mot_de_passe, role=:role";

        $stmt = $this->conn->prepare($query);

        // Nettoyage des données
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->role = htmlspecialchars(strip_tags($this->role));

        // Hash du mot de passe
        $password_hash = password_hash($this->mot_de_passe, PASSWORD_BCRYPT);

        // Liaison des paramètres
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":mot_de_passe", $password_hash);
        $stmt->bindParam(":role", $this->role);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    /**
     * Vérifie si un email existe déjà
     * @param string $email
     * @return bool
     */
    public function emailExists($email) {
        $query = "SELECT id, nom, email, mot_de_passe, role 
                  FROM " . $this->table_name . " 
                  WHERE email = :email LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->nom = $row['nom'];
            $this->email = $row['email'];
            $this->mot_de_passe = $row['mot_de_passe'];
            $this->role = $row['role'];
            return true;
        }

        return false;
    }

    /**
     * Vérifie les identifiants de connexion
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function login($email, $password) {
        if($this->emailExists($email)) {
            return password_verify($password, $this->mot_de_passe);
        }
        return false;
    }

    /**
     * Récupère un utilisateur par son ID
     * @param int $id
     * @return bool
     */
    public function readOne($id) {
        $query = "SELECT id, nom, email, role, created_at, updated_at 
                  FROM " . $this->table_name . " 
                  WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->nom = $row['nom'];
            $this->email = $row['email'];
            $this->role = $row['role'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }

        return false;
    }

    /**
     * Met à jour les informations de l'utilisateur
     * @return bool
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nom=:nom, email=:email, role=:role, updated_at=CURRENT_TIMESTAMP 
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Nettoyage des données
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->role = htmlspecialchars(strip_tags($this->role));

        // Liaison des paramètres
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    /**
     * Supprime un utilisateur
     * @return bool
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    /**
     * Valide les données d'un utilisateur
     * @return array
     */
    public function validate() {
        $errors = [];

        if(empty($this->nom) || strlen($this->nom) < 2) {
            $errors[] = "Le nom doit contenir au moins 2 caractères";
        }

        if(empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide";
        }

        if(empty($this->mot_de_passe) || strlen($this->mot_de_passe) < 6) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
        }

        if(!in_array($this->role, ['admin', 'visiteur'])) {
            $errors[] = "Rôle invalide";
        }

        return $errors;
    }
}
?>
