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
    public $created_at;
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // CRUD : Create
    public function create() {
        if (empty($this->visiteur_id) || empty($this->camping_id) || empty($this->date_debut) || empty($this->date_fin)) {
            return false;
        }
        $sql = "INSERT INTO reservation (visiteur_id, camping_id, date_debut, date_fin) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$this->visiteur_id, $this->camping_id, $this->date_debut, $this->date_fin]);
    }

    // CRUD : Read (une réservation)
    public function read($id) {
        $sql = "SELECT * FROM reservation WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id = $row['id'];
            $this->visiteur_id = $row['visiteur_id'];
            $this->camping_id = $row['camping_id'];
            $this->date_debut = $row['date_debut'];
            $this->date_fin = $row['date_fin'];
            $this->created_at = isset($row['created_at']) ? $row['created_at'] : null;
            return true;
        }
        return false;
    }

    // CRUD : Update
    public function update() {
        $sql = "UPDATE reservation SET visiteur_id = ?, camping_id = ?, date_debut = ?, date_fin = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$this->visiteur_id, $this->camping_id, $this->date_debut, $this->date_fin, $this->id]);
    }

    // CRUD : Delete
    public function delete($id) {
        $sql = "DELETE FROM reservation WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // CRUD : Read all
    public function readAll() {
        $sql = "SELECT * FROM reservation";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
