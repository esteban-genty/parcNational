<?php
/**
 * BaseController - Classe de base pour tous les contrôleurs API
 * Principe SOLID: Single Responsibility - Gère uniquement les réponses HTTP communes
 * 
 * Note: Les headers CORS sont maintenant gérés par config/cors.php (centralisé)
 */
class BaseController {
    
    public function __construct() {
        // Les CORS sont gérés par config/cors.php
        // Plus besoin de setCorsHeaders() ici
    }
    
    /**
     * Retourne une réponse JSON succès
     */
    protected function jsonSuccess($data = [], int $statusCode = 200): void {
        http_response_code($statusCode);
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
        exit();
    }
    
    /**
     * Retourne une réponse JSON erreur
     */
    protected function jsonError(string $message, int $statusCode = 400): void {
        http_response_code($statusCode);
        echo json_encode([
            'success' => false,
            'error' => $message
        ]);
        exit();
    }
    
    /**
     * Récupère les données JSON du corps de la requête
     */
    protected function getJsonInput(): array {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        return $data ?? [];
    }
}
