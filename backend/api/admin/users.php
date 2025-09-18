<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../utils/AuthMiddleware.php';
require_once __DIR__ . '/../../utils/ResponseHelper.php';

/**
 * API de gestion des utilisateurs (Admin seulement)
 * GET /api/admin/users - Liste tous les utilisateurs
 * DELETE /api/admin/users/{id} - Supprime un utilisateur
 */

try {
    $payload = AuthMiddleware::requireAdmin();
    
    if (!$payload) {
        exit(); // AuthMiddleware a déjà envoyé la réponse d'erreur
    }

    $database = new Database();
    $db = $database->getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Liste de tous les utilisateurs
        $query = "SELECT u.id, u.nom, u.email, u.role, u.created_at,
                         v.abonnement, v.carte_membre
                  FROM utilisateur u
                  LEFT JOIN visiteur v ON u.id = v.utilisateur_id
                  ORDER BY u.created_at DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        ResponseHelper::success($users, "Liste des utilisateurs récupérée");

    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Suppression d'un utilisateur
        $path_parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $user_id = end($path_parts);
        
        if (!is_numeric($user_id)) {
            ResponseHelper::error("ID utilisateur invalide", 400);
        }

        // Vérifier que l'admin ne se supprime pas lui-même
        if ($user_id == $payload['user_id']) {
            ResponseHelper::error("Vous ne pouvez pas supprimer votre propre compte", 400);
        }

        $user = new User($db);
        $user->id = $user_id;
        
        if (!$user->readOne($user_id)) {
            ResponseHelper::notFound("Utilisateur");
        }

        if ($user->delete()) {
            ResponseHelper::success(null, "Utilisateur supprimé avec succès");
        } else {
            ResponseHelper::serverError("Erreur lors de la suppression");
        }

    } else {
        ResponseHelper::methodNotAllowed();
    }

} catch (Exception $e) {
    error_log("Erreur admin/users: " . $e->getMessage());
    ResponseHelper::serverError("Erreur lors de la gestion des utilisateurs");
}
?>
