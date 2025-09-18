<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../utils/ResponseHelper.php';

/**
 * API publique des sentiers (Accès libre)
 * GET /api/public/sentiers - Liste des sentiers
 * GET /api/public/sentiers/{id} - Détails d'un sentier
 */

try {
    $database = new Database();
    $db = $database->getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $path_parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $sentier_id = end($path_parts);

        if (is_numeric($sentier_id)) {
            // Détails d'un sentier spécifique
            $query = "SELECT * FROM sentier WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $sentier_id);
            $stmt->execute();
            
            if ($stmt->rowCount() === 0) {
                ResponseHelper::notFound("Sentier");
            }
            
            $sentier = $stmt->fetch(PDO::FETCH_ASSOC);
            ResponseHelper::success($sentier, "Détails du sentier récupérés");
            
        } else {
            // Liste de tous les sentiers
            $query = "SELECT * FROM sentier ORDER BY nom ASC";
            $stmt = $db->prepare($query);
            $stmt->execute();
            
            $sentiers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ResponseHelper::success($sentiers, "Liste des sentiers récupérée");
        }

    } else {
        ResponseHelper::methodNotAllowed();
    }

} catch (Exception $e) {
    error_log("Erreur public/sentiers: " . $e->getMessage());
    ResponseHelper::serverError("Erreur lors de la récupération des sentiers");
}
?>
