<?php
/**
 * BaseController - Classe de base pour tous les contrôleurs API
 * Principe SOLID: Single Responsibility - Gère uniquement les réponses HTTP communes
 */
class BaseController {
    
    public function __construct() {
        $this->setCorsHeaders();
    }
    
    /**
     * Configure les headers CORS une seule fois
     */
    protected function setCorsHeaders(): void {
        // Support de plusieurs ports pour le développement
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if ($origin) {
            header("Access-Control-Allow-Origin: $origin");
        } else {
            header("Access-Control-Allow-Origin: http://localhost:5173");
        }
        
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Credentials: true");
        header("Content-Type: application/json");
        
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
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
