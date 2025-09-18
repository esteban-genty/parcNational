<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../utils/Validator.php';
require_once __DIR__ . '/../../utils/ResponseHelper.php';
require_once __DIR__ . '/../../utils/JWTHandler.php';

/**
 * API de connexion pour le Parc National des Calanques
 * POST /api/auth/login
 */

// Vérification de la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ResponseHelper::methodNotAllowed();
}

try {
    // Récupération des données JSON
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        ResponseHelper::error("Données JSON invalides", 400);
    }

    // Validation des champs requis
    if (empty($input['email']) || empty($input['mot_de_passe'])) {
        ResponseHelper::error("Email et mot de passe requis", 400, "MISSING_CREDENTIALS");
    }

    // Validation de l'email
    if (!Validator::validateEmail($input['email'])) {
        ResponseHelper::error("Format d'email invalide", 400, "INVALID_EMAIL");
    }

    // Connexion à la base de données
    $database = new Database();
    $db = $database->getConnection();

    // Création de l'objet User
    $user = new User($db);
    
    // Tentative de connexion
    if (!$user->login($input['email'], $input['mot_de_passe'])) {
        ResponseHelper::error("Identifiants incorrects", 401, "INVALID_CREDENTIALS");
    }

    // Génération des tokens JWT
    $payload = JWTHandler::createUserPayload($user);
    $token = JWTHandler::generateToken($payload);
    $refreshToken = JWTHandler::generateRefreshToken($user->id);

    // Réponse de succès
    ResponseHelper::success([
        'user' => [
            'id' => $user->id,
            'nom' => $user->nom,
            'email' => $user->email,
            'role' => $user->role
        ],
        'tokens' => [
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'expires_in' => JWT_EXPIRATION_TIME
        ]
    ], "Connexion réussie");

} catch (Exception $e) {
    error_log("Erreur connexion: " . $e->getMessage());
    ResponseHelper::serverError("Erreur lors de la connexion");
}
?>
