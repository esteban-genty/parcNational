<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../utils/ResponseHelper.php';
require_once __DIR__ . '/../../utils/JWTHandler.php';

/**
 * API de rafraîchissement de token
 * POST /api/auth/refresh
 */

// Vérification de la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ResponseHelper::methodNotAllowed();
}

try {
    // Récupération des données JSON
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || empty($input['refresh_token'])) {
        ResponseHelper::error("Token de rafraîchissement requis", 400, "MISSING_REFRESH_TOKEN");
    }

    // Vérification du token de rafraîchissement
    $payload = JWTHandler::verifyRefreshToken($input['refresh_token']);
    
    if (!$payload) {
        ResponseHelper::error("Token de rafraîchissement invalide ou expiré", 401, "INVALID_REFRESH_TOKEN");
    }

    // Connexion à la base de données
    $database = new Database();
    $db = $database->getConnection();

    // Récupération des informations utilisateur
    $user = new User($db);
    if (!$user->readOne($payload['user_id'])) {
        ResponseHelper::error("Utilisateur non trouvé", 404, "USER_NOT_FOUND");
    }

    // Génération de nouveaux tokens
    $newPayload = JWTHandler::createUserPayload($user);
    $newToken = JWTHandler::generateToken($newPayload);
    $newRefreshToken = JWTHandler::generateRefreshToken($user->id);

    // Réponse de succès
    ResponseHelper::success([
        'tokens' => [
            'access_token' => $newToken,
            'refresh_token' => $newRefreshToken,
            'expires_in' => JWT_EXPIRATION_TIME
        ]
    ], "Token rafraîchi avec succès");

} catch (Exception $e) {
    error_log("Erreur rafraîchissement token: " . $e->getMessage());
    ResponseHelper::serverError("Erreur lors du rafraîchissement du token");
}
?>
