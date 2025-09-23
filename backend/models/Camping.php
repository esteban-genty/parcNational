<?php
/**
 * Modèle Camping : représente un camping dans le parc national
 */
class Camping {
    public $id;
    public $nom;
    public $localisation;
    public $capacite;
    public $created_at;
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // CRUD : Create
    public function create() {
        // Validation des données
        if (empty($this->nom) || $this->capacite <= 0) {
            // Si le nom est vide ou la capacité <= 0, on refuse la création
            return false;
        }
        $sql = "INSERT INTO camping (nom, localisation, capacite) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$this->nom, $this->localisation, $this->capacite]);
    }

    // CRUD : Read (un camping)
    public function read($id) {
        $sql = "SELECT * FROM camping WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        if ($data) {
            $this->id = $data['id'];
            $this->nom = $data['nom'];
            $this->localisation = $data['localisation'];
            $this->capacite = $data['capacite'];
            $this->created_at = $data['created_at'];
            return true;
        }
        return false;
    }

    // CRUD : Update
    public function update() {
        $sql = "UPDATE camping SET nom = ?, localisation = ?, capacite = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$this->nom, $this->localisation, $this->capacite, $this->id]);
    }

    // CRUD : Delete
    public function delete($id) {
        $sql = "DELETE FROM camping WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // CRUD : Read all
    public function readAll() {
        $sql = "SELECT * FROM camping";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
