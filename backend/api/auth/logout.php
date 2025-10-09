<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../utils/AuthMiddleware.php';
require_once __DIR__ . '/../../utils/ResponseHelper.php';

/**
 * API de déconnexion
 * POST /api/auth/logout
 * Note: Avec JWT, la déconnexion côté serveur est limitée
 * Le client doit supprimer le token de son stockage local
 */

// Vérification de la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ResponseHelper::methodNotAllowed();
}

try {
    // Vérification de l'authentification (optionnelle pour logout)
    $payload = AuthMiddleware::authenticate();
    
    // Dans une implémentation plus avancée, on pourrait :
    // - Ajouter le token à une blacklist
    // - Invalider le refresh token en base
    // - Logger la déconnexion
    
    if ($payload) {
        error_log("Déconnexion utilisateur ID: " . $payload['user_id']);
    }

    ResponseHelper::success(null, "Déconnexion réussie");

} catch (Exception $e) {
    error_log("Erreur déconnexion: " . $e->getMessage());
    ResponseHelper::success(null, "Déconnexion réussie"); // Toujours succès pour logout
}
?>
