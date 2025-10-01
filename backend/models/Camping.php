<?php
/**
 * Modèle Camping : représente un camping dans le parc national
 */
class Camping {
    public $id;
    public $nom;
    public $localisation;
    public $capacite;
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findAll() {
        $sql = "SELECT * FROM camping";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $sql = "SELECT * FROM camping WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        if (empty($data['nom']) || empty($data['localisation']) || empty($data['capacite']) || $data['capacite'] <= 0) {
            return false;
        }
        $sql = "INSERT INTO camping (nom, localisation, capacite) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['nom'],
            $data['localisation'],
            $data['capacite']
        ]);
        $id = $this->db->lastInsertId();
        return $this->findById($id);
    }

    public function update($data) {
        if (empty($data['id'])) return false;
        $sql = "UPDATE camping SET nom = ?, localisation = ?, capacite = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['nom'] ?? null,
            $data['localisation'] ?? null,
            $data['capacite'] ?? null,
            $data['id']
        ]);
        return $this->findById($data['id']);
    }

    public function delete($id) {
        $row = $this->findById($id);
        $sql = "DELETE FROM camping WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $row;
    }
        // Ajout méthode read pour les tests
        public function read($id) {
            $row = $this->findById($id);
            return $row ? true : false;
        }
}
