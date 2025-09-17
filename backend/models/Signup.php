<?php

class Signup extends Database
{
    private PDO $db;

    public function __construct()
    {
       
        $this->db = $this->connect();
    }

    /**
     * Vérifie les identifiants de connexion
     *
     * @param string $email
     * @param string $mot_de_passe
     * @return bool
     */
    public function login(string $email, string $mot_de_passe): bool
    {
        
        $sql = "SELECT * FROM UTILISATEUR WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":email" => $email]);

        // Récupération des données utilisateur
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe
        return $user && password_verify($mot_de_passe, $user["mot_de_passe"]);
    }
}

?>
