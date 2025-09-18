<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Modèle Visiteur pour la gestion des profils visiteurs
 */
class Visiteur {
    private $conn;
    private $table_name = "visiteur";

    // Propriétés du visiteur
    public $id;
    public $utilisateur_id;
    public $abonnement;
    public $carte_membre;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Crée un profil visiteur
     * @return bool
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET utilisateur_id=:utilisateur_id, abonnement=:abonnement, carte_membre=:carte_membre";

        $stmt = $this->conn->prepare($query);

        // Nettoyage des données
        $this->abonnement = htmlspecialchars(strip_tags($this->abonnement));
        $this->carte_membre = htmlspecialchars(strip_tags($this->carte_membre));

        // Liaison des paramètres
        $stmt->bindParam(":utilisateur_id", $this->utilisateur_id);
        $stmt->bindParam(":abonnement", $this->abonnement);
        $stmt->bindParam(":carte_membre", $this->carte_membre);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    /**
     * Récupère un visiteur par son utilisateur_id
     * @param int $utilisateur_id
     * @return bool
     */
    public function readByUserId($utilisateur_id) {
        $query = "SELECT v.*, u.nom, u.email 
                  FROM " . $this->table_name . " v
                  LEFT JOIN utilisateur u ON v.utilisateur_id = u.id
                  WHERE v.utilisateur_id = :utilisateur_id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":utilisateur_id", $utilisateur_id);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->utilisateur_id = $row['utilisateur_id'];
            $this->abonnement = $row['abonnement'];
            $this->carte_membre = $row['carte_membre'];
            $this->created_at = $row['created_at'];
            return true;
        }

        return false;
    }

    /**
     * Met à jour le profil visiteur
     * @return bool
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET abonnement=:abonnement, carte_membre=:carte_membre 
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Nettoyage des données
        $this->abonnement = htmlspecialchars(strip_tags($this->abonnement));
        $this->carte_membre = htmlspecialchars(strip_tags($this->carte_membre));

        // Liaison des paramètres
        $stmt->bindParam(":abonnement", $this->abonnement);
        $stmt->bindParam(":carte_membre", $this->carte_membre);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }
}
?>
