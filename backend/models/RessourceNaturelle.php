<?php
/**
 * Modèle RessourceNaturelle : représente une ressource naturelle du parc
 */
class RessourceNaturelle {
    public $id;
    public $type;
    public $nom;
    public $etat;
    public $created_at;
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // CRUD : Create
    public function create() {
        if (empty($this->type)) {
            return false;
        }
        $sql = "INSERT INTO ressource_naturelle (type, nom, etat) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$this->type, $this->nom, $this->etat]);
    }

    // CRUD : Read (une ressource)
    public function read($id) {
        $sql = "SELECT * FROM ressource_naturelle WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        if ($data) {
            $this->id = $data['id'];
            $this->type = $data['type'];
            $this->nom = $data['nom'];
            $this->etat = $data['etat'];
            $this->created_at = $data['created_at'];
            return true;
        }
        return false;
    }

    // CRUD : Update
    public function update() {
        if (empty($this->type) || empty($this->id)) {
            return false;
        }
        $sql = "UPDATE ressource_naturelle SET type = ?, nom = ?, etat = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$this->type, $this->nom, $this->etat, $this->id]);
    }

    // CRUD : Delete
    public function delete($id) {
        $sql = "DELETE FROM ressource_naturelle WHERE id = ?";
        $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $data = $stmt->fetch();
            if ($data) {
                $this->id = $data['id'];
                $this->type = $data['type'];
                $this->nom = $data['nom'];
                $this->etat = $data['etat'];
                $this->created_at = isset($data['created_at']) ? $data['created_at'] : null;
        }
    }
}