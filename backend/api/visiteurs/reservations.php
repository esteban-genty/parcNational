<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Visiteur.php';
require_once __DIR__ . '/../../utils/AuthMiddleware.php';
require_once __DIR__ . '/../../utils/ResponseHelper.php';

/**
 * API de gestion des réservations (Visiteurs authentifiés)
 * GET /api/visiteur/reservations - Mes réservations
 * POST /api/visiteur/reservations - Créer une réservation
 */

try {
    $payload = AuthMiddleware::requireAuth();
    
    if (!$payload) {
        exit(); // AuthMiddleware a déjà envoyé la réponse d'erreur
    }

    $database = new Database();
    $db = $database->getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Récupération des réservations de l'utilisateur connecté
        $query = "SELECT r.*, c.nom as camping_nom, c.localisation
                  FROM reservation r
                  JOIN camping c ON r.camping_id = c.id
                  JOIN visiteur v ON r.visiteur_id = v.id
                  WHERE v.utilisateur_id = :user_id
                  ORDER BY r.date_debut DESC";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $payload['user_id']);
        $stmt->execute();
        
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        ResponseHelper::success($reservations, "Réservations récupérées");

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Création d'une nouvelle réservation
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            ResponseHelper::error("Données JSON invalides", 400);
        }

        // Validation des champs requis
        $required_fields = ['camping_id', 'date_debut', 'date_fin'];
        foreach ($required_fields as $field) {
            if (empty($input[$field])) {
                ResponseHelper::error("Le champ {$field} est requis", 400);
            }
        }

        // Vérifier que l'utilisateur a un profil visiteur
        $visiteur = new Visiteur($db);
        if (!$visiteur->readByUserId($payload['user_id'])) {
            ResponseHelper::error("Profil visiteur non trouvé", 404);
        }

        // Vérifier que le camping existe
        $camping_query = "SELECT id FROM camping WHERE id = :camping_id";
        $camping_stmt = $db->prepare($camping_query);
        $camping_stmt->bindParam(':camping_id', $input['camping_id']);
        $camping_stmt->execute();
        
        if ($camping_stmt->rowCount() === 0) {
            ResponseHelper::error("Camping non trouvé", 404);
        }

        // Vérifier les dates
        $date_debut = new DateTime($input['date_debut']);
        $date_fin = new DateTime($input['date_fin']);
        $today = new DateTime();
        
        if ($date_debut < $today) {
            ResponseHelper::error("La date de début ne peut pas être dans le passé", 400);
        }
        
        if ($date_fin <= $date_debut) {
            ResponseHelper::error("La date de fin doit être après la date de début", 400);
        }

        // Créer la réservation
        $reservation_query = "INSERT INTO reservation (visiteur_id, camping_id, date_debut, date_fin) 
                             VALUES (:visiteur_id, :camping_id, :date_debut, :date_fin)";
        
        $reservation_stmt = $db->prepare($reservation_query);
        $reservation_stmt->bindParam(':visiteur_id', $visiteur->id);
        $reservation_stmt->bindParam(':camping_id', $input['camping_id']);
        $reservation_stmt->bindParam(':date_debut', $input['date_debut']);
        $reservation_stmt->bindParam(':date_fin', $input['date_fin']);
        
        if ($reservation_stmt->execute()) {
            $reservation_id = $db->lastInsertId();
            ResponseHelper::created(['reservation_id' => $reservation_id], "Réservation créée avec succès");
        } else {
            ResponseHelper::serverError("Erreur lors de la création de la réservation");
        }

    } else {
        ResponseHelper::methodNotAllowed();
    }

} catch (Exception $e) {
    error_log("Erreur visiteur/reservations: " . $e->getMessage());
    ResponseHelper::serverError("Erreur lors de la gestion des réservations");
}
?>
