<?php
/**
 * Modèle Reservation : représente une réservation dans le parc national
 */
class Reservation {
    public $id;
    public $visiteur_id;
    public $camping_id;
    public $date_debut;
    public $date_fin;
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findAll() {
        $sql = "SELECT * FROM reservation";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $sql = "SELECT * FROM reservation WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        if (empty($data['visiteur_id']) || empty($data['camping_id']) || empty($data['date_debut']) || empty($data['date_fin'])) {
            return false;
        }
        $sql = "INSERT INTO reservation (visiteur_id, camping_id, date_debut, date_fin) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['visiteur_id'],
            $data['camping_id'],
            $data['date_debut'],
            $data['date_fin']
        ]);
        $id = $this->db->lastInsertId();
        return $this->findById($id);
    }

    public function update($data) {
        if (empty($data['id'])) return false;
        $sql = "UPDATE reservation SET visiteur_id = ?, camping_id = ?, date_debut = ?, date_fin = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['visiteur_id'] ?? null,
            $data['camping_id'] ?? null,
            $data['date_debut'] ?? null,
            $data['date_fin'] ?? null,
            $data['id']
        ]);
        return $this->findById($data['id']);
    }

    public function delete($id) {
        $row = $this->findById($id);
        $sql = "DELETE FROM reservation WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $row;
    }
        // Ajout méthode read pour les tests
        public function read($id) {
            $row = $this->findById($id);
            return $row ? true : false;
        }
        public function selectReservation($id) 
        {
           $query = "SELECT r.*, c.nom as camping_nom, c.localisation
                  FROM reservation r
                  JOIN camping c ON r.camping_id = c.id
                  JOIN visiteur v ON r.visiteur_id = v.id
                  WHERE v.utilisateur_id = :user_id
                  ORDER BY r.date_debut DESC";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);

        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        return $result; 


        }
}