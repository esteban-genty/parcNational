<?php
/**
 * Modèle Notification - Pattern SOLID
 * Notifications RÉELLES basées sur les événements du système
 */
require_once __DIR__ . '/Database.php';

class Notification extends Database {
    private $db;

    public function __construct() {
        $this->db = $this->connect();
    }

    /**
     * Crée une notification système (danger sentier, capacité camping, etc.)
     */
    public function createSystemNotification(string $type, string $entityId, string $message): ?array {
        $titres = [
            'sentier_danger' => '⚠️ Alerte Sentier',
            'camping_plein' => '🏕️ Camping Complet',
            'ressource_menacee' => '🌿 Ressource en Danger',
            'reservation_confirmee' => '✅ Réservation Confirmée',
            'meteo_alerte' => '🌧️ Alerte Météo'
        ];
        
        $titre = $titres[$type] ?? '📢 Notification';
        
        // Vérifier si les colonnes type et entity_id existent
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO notification (titre, message, type, entity_id, date_envoi, created_at) 
                 VALUES (?, ?, ?, ?, NOW(), NOW())"
            );
            $stmt->execute([$titre, $message, $type, $entityId]);
        } catch (PDOException $e) {
            // Si les colonnes n'existent pas, utiliser la version simple
            $stmt = $this->db->prepare(
                "INSERT INTO notification (titre, message, date_envoi) 
                 VALUES (?, ?, NOW())"
            );
            $stmt->execute([$titre, $message]);
        }
        
        return $this->findById($this->db->lastInsertId());
    }

    /**
     * Récupère les notifications actives (dernières 24h ou non lues)
     */
    public function getActiveNotifications(int $limit = 10): array {
        $sql = "SELECT * FROM notification 
                WHERE DATE(date_envoi) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                ORDER BY date_envoi DESC 
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifie les sentiers dangereux et crée des notifications
     */
    public function checkDangerousSentiers(): array {
        $sql = "SELECT id, nom, difficulte, longueur 
                FROM sentier 
                WHERE difficulte IN ('Difficile', 'Très difficile')
                AND id NOT IN (
                    SELECT entity_id FROM notification 
                    WHERE type = 'sentier_danger' 
                    AND DATE(date_envoi) = CURDATE()
                )";
        
        $stmt = $this->db->query($sql);
        $sentiers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $notifications = [];
        foreach ($sentiers as $sentier) {
            $message = sprintf(
                "Le sentier '%s' est classé %s (%s km). Prudence recommandée.",
                $sentier['nom'],
                $sentier['difficulte'],
                $sentier['longueur']
            );
            
            $notif = $this->createSystemNotification('sentier_danger', $sentier['id'], $message);
            if ($notif) $notifications[] = $notif;
        }
        
        return $notifications;
    }

    /**
     * Vérifie la capacité des campings
     */
    public function checkCampingCapacity(): array {
        $sql = "SELECT c.id, c.nom, c.capacite, 
                COUNT(r.id) as reservations_actives
                FROM camping c
                LEFT JOIN reservation r ON r.camping_id = c.id 
                    AND r.date_fin >= CURDATE()
                GROUP BY c.id
                HAVING reservations_actives >= c.capacite * 0.8";
        
        $stmt = $this->db->query($sql);
        $campings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $notifications = [];
        foreach ($campings as $camping) {
            $pourcentage = ($camping['reservations_actives'] / $camping['capacite']) * 100;
            $message = sprintf(
                "Le camping '%s' est occupé à %.0f%% (%d/%d places).",
                $camping['nom'],
                $pourcentage,
                $camping['reservations_actives'],
                $camping['capacite']
            );
            
            $notif = $this->createSystemNotification('camping_plein', $camping['id'], $message);
            if ($notif) $notifications[] = $notif;
        }
        
        return $notifications;
    }

    public function findAll(): array {
        return $this->getActiveNotifications(50);
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM notification WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM notification WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Trouve les notifications pour un utilisateur spécifique
     * @param int $userId ID de l'utilisateur
     * @return array Liste des notifications
     */
    public function findByUserId(int $userId): array {
        // Pour l'instant, retourne toutes les notifications actives
        // À améliorer pour filtrer par utilisateur si nécessaire
        return $this->getActiveNotifications(20);
    }

    /**
     * Crée une notification simple (compatibilité avec ancien code)
     * @param array $data Données de la notification
     * @return array|false Notification créée ou false en cas d'erreur
     */
    public function create(array $data) {
        try {
            $titre = $data['titre'] ?? 'Notification';
            $message = $data['message'] ?? '';
            $dateEnvoi = $data['date_envoi'] ?? date('Y-m-d');
            
            $stmt = $this->db->prepare(
                "INSERT INTO notification (titre, message, date_envoi, created_at) 
                 VALUES (?, ?, ?, NOW())"
            );
            $stmt->execute([$titre, $message, $dateEnvoi]);
            
            return $this->findById($this->db->lastInsertId());
        } catch (PDOException $e) {
            error_log("Erreur création notification: " . $e->getMessage());
            return false;
        }
    }
}
