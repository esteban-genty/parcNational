<?php
require_once __DIR__ . '/../config/config.php';

/**
 * Gestionnaire JWT pour l'authentification du Parc National des Calanques
 * Implémentation native PHP sans dépendances externes
 */
class JWTHandler {
    
    /**
     * Encode en Base64 URL-safe
     * @param string $data
     * @return string
     */
    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Decode en Base64 URL-safe
     * @param string $data
     * @return string
     */
    private static function base64UrlDecode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    /**
     * Génère un token JWT
     * @param array $payload
     * @return string
     */
    public static function generateToken($payload) {
        // Header JWT
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => JWT_ALGORITHM
        ]);

        // Payload avec données utilisateur et timestamps
        $payload['iat'] = time(); // Issued at
        $payload['exp'] = time() + JWT_EXPIRATION_TIME; // Expiration
        $payload['iss'] = APP_NAME; // Issuer
        
        $payload = json_encode($payload);

        // Encodage Base64 URL-safe
        $headerEncoded = self::base64UrlEncode($header);
        $payloadEncoded = self::base64UrlEncode($payload);

        // Signature
        $signature = hash_hmac('sha256', $headerEncoded . "." . $payloadEncoded, JWT_SECRET_KEY, true);
        $signatureEncoded = self::base64UrlEncode($signature);

        // Token final
        return $headerEncoded . "." . $payloadEncoded . "." . $signatureEncoded;
    }

    /**
     * Vérifie et décode un token JWT
     * @param string $token
     * @return array|false
     */
    public static function verifyToken($token) {
        try {
            // Séparation des parties du token
            $parts = explode('.', $token);
            
            if (count($parts) !== 3) {
                return false;
            }

            list($headerEncoded, $payloadEncoded, $signatureEncoded) = $parts;

            // Vérification de la signature
            $signature = self::base64UrlDecode($signatureEncoded);
            $expectedSignature = hash_hmac('sha256', $headerEncoded . "." . $payloadEncoded, JWT_SECRET_KEY, true);

            if (!hash_equals($signature, $expectedSignature)) {
                return false;
            }

            // Décodage du payload
            $payload = json_decode(self::base64UrlDecode($payloadEncoded), true);

            if (!$payload) {
                return false;
            }

            // Vérification de l'expiration
            if (isset($payload['exp']) && $payload['exp'] < time()) {
                return false;
            }

            // Vérification de l'issuer
            if (isset($payload['iss']) && $payload['iss'] !== APP_NAME) {
                return false;
            }

            return $payload;

        } catch (Exception $e) {
            error_log("Erreur JWT: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Extrait le token du header Authorization
     * @return string|false
     */
    public static function getBearerToken() {
        $headers = getallheaders();
        
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                return $matches[1];
            }
        }
        
        return false;
    }

    /**
     * Génère un token de rafraîchissement
     * @param int $userId
     * @return string
     */
    public static function generateRefreshToken($userId) {
        $payload = [
            'user_id' => $userId,
            'type' => 'refresh',
            'exp' => time() + (7 * 24 * 3600) // 7 jours
        ];
        
        return self::generateToken($payload);
    }

    /**
     * Valide un token de rafraîchissement
     * @param string $token
     * @return array|false
     */
    public static function verifyRefreshToken($token) {
        $payload = self::verifyToken($token);
        
        if ($payload && isset($payload['type']) && $payload['type'] === 'refresh') {
            return $payload;
        }
        
        return false;
    }

    /**
     * Crée un payload utilisateur pour le JWT
     * @param object $user
     * @return array
     */
    public static function createUserPayload($user) {
        return [
            'user_id' => $user->id,
            'email' => $user->email,
            'nom' => $user->nom,
            'role' => $user->role
        ];
    }
}
?>
