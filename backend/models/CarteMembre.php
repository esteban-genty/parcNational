<?php
/**
 * Modèle CarteMembre : représente une carte de membre du parc
 */
class CarteMembre {
    public $id;
    public $numero_carte;
    public $type_carte;
    public $date_expiration;
    public $created_at;
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // CRUD : Create
    public function create() {
        if (empty($this->numero_carte) || empty($this->type_carte) || empty($this->date_expiration)) {
            return false;
        }
        $sql = "INSERT INTO carte_membre (numero_carte, type_carte, date_expiration) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$this->numero_carte, $this->type_carte, $this->date_expiration]);
    }

    // CRUD : Read (une carte)
    public function read($id) {
        $sql = "SELECT * FROM carte_membre WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        if ($data) {
            $this->id = $data['id'];
            $this->numero_carte = $data['numero_carte'];
            $this->type_carte = $data['type_carte'];
            $this->date_expiration = $data['date_expiration'];
            $this->created_at = $data['created_at'];
            return true;
        }
        return false;
    }

    // CRUD : Update
    public function update() {
        if (empty($this->numero_carte) || empty($this->type_carte) || empty($this->date_expiration) || empty($this->id)) {
            return false;
        }
        $sql = "UPDATE carte_membre SET numero_carte = ?, type_carte = ?, date_expiration = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$this->numero_carte, $this->type_carte, $this->date_expiration, $this->id]);
    }

    // CRUD : Delete
    public function delete($id) {
        $sql = "DELETE FROM carte_membre WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // CRUD : Read all
    public function readAll() {
        $sql = "SELECT * FROM carte_membre";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
