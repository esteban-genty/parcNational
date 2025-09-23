<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Visiteur.php';

/**
 * Classe Auth pour gérer l'authentification (inscription et connexion)
 */
class Auth {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Inscrit un nouvel utilisateur
     * @param string $nom
     * @param string $email
     * @param string $mot_de_passe
     * @param string $role
     * @return bool
     */
    public function register($nom, $email, $mot_de_passe, $role) {
        $conn = $this->db->getConnection();
        $user = new User($conn);

        // Vérifier si l'email existe déjà
        if ($user->emailExists($email)) {
            return false;
        }

        // Attribuer les valeurs
        $user->nom = $nom;
        $user->email = $email;
        $user->mot_de_passe = $mot_de_passe;
        $user->role = $role;

        // Validation
        $errors = $user->validate();
        if (!empty($errors)) {
            return false;
        }

        // Début de transaction
        $conn->beginTransaction();

        try {
            // Créer l'utilisateur
            if (!$user->create()) {
                throw new Exception("Erreur lors de la création de l'utilisateur");
            }

            // Si c'est un visiteur, créer le profil visiteur
            if ($user->role === 'visiteur') {
                $visiteur = new Visiteur($conn);
                $visiteur->utilisateur_id = $user->id;
                if (!$visiteur->create()) {
                    throw new Exception("Erreur lors de la création du profil visiteur");
                }
            }

            // Valider la transaction
            $conn->commit();
            return true;

        } catch (Exception $e) {
            // Annuler la transaction
            $conn->rollback();
            return false;
        }
    }

    /**
     * Connecte un utilisateur
     * @param string $email
     * @param string $mot_de_passe
     * @return bool
     */
    public function login($email, $mot_de_passe) {
        $conn = $this->db->getConnection();
        $user = new User($conn);
        return $user->login($email, $mot_de_passe);
    }
}
?>