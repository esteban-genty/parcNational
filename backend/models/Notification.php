<?php
/**
 * Modèle Notification : représente une notification envoyée aux utilisateurs
 */
class Notification {
    public $id;
    public $titre;
    public $message;
    public $date_envoi;
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findAll() {
        $sql = "SELECT * FROM notification";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $sql = "SELECT * FROM notification WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 👤 Récupère toutes les notifications d'un utilisateur spécifique
     * @param int $utilisateur_id - ID de l'utilisateur
     * @return array - Liste des notifications
     */
    public function findByUserId($utilisateur_id) {
        $sql = "SELECT * FROM notification WHERE utilisateur_id = ? ORDER BY date_envoi DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$utilisateur_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        if (empty($data['titre']) || empty($data['message']) || empty($data['date_envoi'])) {
            return false;
        }
        $sql = "INSERT INTO notification (titre, message, date_envoi) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['titre'],
            $data['message'],
            $data['date_envoi']
        ]);
        $id = $this->db->lastInsertId();
        return $this->findById($id);
    }

    public function update($data) {
        if (empty($data['id'])) return false;
        $sql = "UPDATE notification SET titre = ?, message = ?, date_envoi = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['titre'] ?? null,
            $data['message'] ?? null,
            $data['date_envoi'] ?? null,
            $data['id']
        ]);
        return $this->findById($data['id']);
    }

    public function delete($id) {
        $row = $this->findById($id);
        $sql = "DELETE FROM notification WHERE id = ?";
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