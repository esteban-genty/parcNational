<?php
/**
 * Modèle Sentier : représente un sentier dans le parc national
 */
class Sentier {
    public $id;
    public $nom;
    public $difficulte;
    public $description;
    public $created_at;
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // CRUD : Create
    public function create() {
        if (empty($this->nom) || empty($this->difficulte)) {
            return ['success' => false, 'message' => 'Nom et difficulté requis'];
        }
        $sql = "INSERT INTO sentier (nom, difficulte, description) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([$this->nom, $this->difficulte, $this->description])) {
            $this->id = $this->db->lastInsertId();
            return ['success' => true, 'id' => $this->id];
        }
        return ['success' => false, 'message' => 'Erreur lors de la création'];
    }

    // CRUD : Read (un sentier)
    public function read($id) {
        $sql = "SELECT * FROM sentier WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id = $row['id'];
            $this->nom = $row['nom'];
            $this->difficulte = $row['difficulte'];
            $this->description = $row['description'];
            $this->created_at = isset($row['created_at']) ? $row['created_at'] : null;
            return true;
        }
        return false;
    }

    // CRUD : Update
    public function update() {
        $sql = "UPDATE sentier SET nom = ?, difficulte = ?, description = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$this->nom, $this->difficulte, $this->description, $this->id]);
    }

    // CRUD : Delete
    public function delete($id) {
        $sql = "DELETE FROM sentier WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // CRUD : Read all
    public function readAll() {
        $sql = "SELECT * FROM sentier";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
