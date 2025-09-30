<?php
/**
 * Modèle Notification : représente une notification envoyée aux utilisateurs
 */
class Notification {
    public $id;
    public $titre;
    public $message;
    public $date_envoi;
    public $created_at;
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // CRUD : Create
    public function create() {
        if (empty($this->titre) || empty($this->message) || empty($this->date_envoi)) {
            return false;
        }
        $sql = "INSERT INTO notification (titre, message, date_envoi) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$this->titre, $this->message, $this->date_envoi]);
    }

    // CRUD : Read (une notification)
    public function read($id) {
        $sql = "SELECT * FROM notification WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        if ($data) {
            $this->id = $data['id'];
            $this->titre = $data['titre'];
            $this->message = $data['message'];
            $this->date_envoi = $data['date_envoi'];
            $this->created_at = $data['created_at'];
            return true;
        }
        return false;
    }

    // CRUD : Update
    public function update() {
        if (empty($this->titre) || empty($this->message) || empty($this->date_envoi) || empty($this->id)) {
            return false;
        }
        $sql = "UPDATE notification SET titre = ?, message = ?, date_envoi = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$this->titre, $this->message, $this->date_envoi, $this->id]);
    }

    // CRUD : Delete
    public function delete($id) {
    $sql = "DELETE FROM notification WHERE id = ?";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([$id]);
}
}