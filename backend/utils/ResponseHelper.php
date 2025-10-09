<?php
/**
 * Classe utilitaire pour standardiser les réponses API
 */
class ResponseHelper {
    
    /**
     * Envoie une réponse de succès
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     */
    public static function success($data = null, $message = "Succès", $statusCode = 200) {
        http_response_code($statusCode);
        
        $response = [
            'success' => true,
            'message' => $message
        ];
        
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * Envoie une réponse d'erreur
     * @param string $message
     * @param int $statusCode
     * @param string $errorCode
     * @param array $errors
     */
    public static function error($message, $statusCode = 400, $errorCode = null, $errors = []) {
        http_response_code($statusCode);
        
        $response = [
            'success' => false,
            'message' => $message
        ];
        
        if ($errorCode) {
            $response['error_code'] = $errorCode;
        }
        
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * Envoie une réponse de validation échouée
     * @param array $errors
     */
    public static function validationError($errors) {
        self::error("Erreurs de validation", 422, "VALIDATION_ERROR", $errors);
    }

    /**
     * Envoie une réponse pour ressource non trouvée
     * @param string $resource
     */
    public static function notFound($resource = "Ressource") {
        self::error($resource . " non trouvé(e)", 404, "NOT_FOUND");
    }

    /**
     * Envoie une réponse pour erreur serveur
     * @param string $message
     */
    public static function serverError($message = "Erreur interne du serveur") {
        self::error($message, 500, "SERVER_ERROR");
    }

    /**
     * Envoie une réponse de création réussie
     * @param mixed $data
     * @param string $message
     */
    public static function created($data = null, $message = "Créé avec succès") {
        self::success($data, $message, 201);
    }

    /**
     * Envoie une réponse pour méthode non autorisée
     */
    public static function methodNotAllowed() {
        self::error("Méthode non autorisée", 405, "METHOD_NOT_ALLOWED");
    }
}
?>
