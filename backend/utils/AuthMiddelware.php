<?php
require_once __DIR__ . '/JWTHandler.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php';

/**
 * Middleware d'authentification pour protéger les routes
 */
class AuthMiddleware {
    
    /**
     * Vérifie l'authentification de l'utilisateur
     * @return array|false
     */
    public static function authenticate() {
        $token = JWTHandler::getBearerToken();
        
        if (!$token) {
            self::sendUnauthorizedResponse("Token manquant");
            return false;
        }

        $payload = JWTHandler::verifyToken($token);
        
        if (!$payload) {
            self::sendUnauthorizedResponse("Token invalide ou expiré");
            return false;
        }

        return $payload;
    }

    /**
     * Vérifie si l'utilisateur a le rôle requis
     * @param string $requiredRole
     * @return array|false
     */
    public static function requireRole($requiredRole) {
        $payload = self::authenticate();
        
        if (!$payload) {
            return false;
        }

        if ($payload['role'] !== $requiredRole) {
            self::sendForbiddenResponse("Accès refusé - Rôle insuffisant");
            return false;
        }

        return $payload;
    }

    /**
     * Vérifie si l'utilisateur est admin
     * @return array|false
     */
    public static function requireAdmin() {
        return self::requireRole('admin');
    }

    /**
     * Vérifie si l'utilisateur est connecté (admin ou visiteur)
     * @return array|false
     */
    public static function requireAuth() {
        return self::authenticate();
    }

    /**
     * Vérifie si l'utilisateur peut accéder à ses propres données
     * @param int $userId
     * @return array|false
     */
    public static function requireOwnershipOrAdmin($userId) {
        $payload = self::authenticate();
        
        if (!$payload) {
            return false;
        }

        // Admin peut tout voir, utilisateur ne peut voir que ses données
        if ($payload['role'] === 'admin' || $payload['user_id'] == $userId) {
            return $payload;
        }

        self::sendForbiddenResponse("Accès refusé - Vous ne pouvez accéder qu'à vos propres données");
        return false;
    }

    /**
     * Envoie une réponse 401 Unauthorized
     * @param string $message
     */
    private static function sendUnauthorizedResponse($message) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => $message,
            'error_code' => 'UNAUTHORIZED'
        ]);
        exit();
    }

    /**
     * Envoie une réponse 403 Forbidden
     * @param string $message
     */
    private static function sendForbiddenResponse($message) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => $message,
            'error_code' => 'FORBIDDEN'
        ]);
        exit();
    }

    /**
     * Récupère les informations complètes de l'utilisateur connecté
     * @return User|false
     */
    public static function getCurrentUser() {
        $payload = self::authenticate();
        
        if (!$payload) {
            return false;
        }

        try {
            $database = new Database();
            $db = $database->getConnection();
            
            $user = new User($db);
            if ($user->readOne($payload['user_id'])) {
                return $user;
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Erreur getCurrentUser: " . $e->getMessage());
            return false;
        }
    }
}
?>
