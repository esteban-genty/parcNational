<?php
/**
 * API Camping Unifiée - Public + Admin
 * GET: Public | POST/PUT/DELETE: Admin seulement
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Camping.php';
require_once __DIR__ . '/../utils/AuthMiddelware.php';
require_once __DIR__ . '/../utils/BaseController.php';

class CampingController extends BaseController {
    private Camping $model;
    
    public function __construct() {
        $this->setCorsHeaders();
        $this->model = new Camping();
    }
    
    public function handle(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        
        switch ($method) {
            case 'GET':
                $this->handleGet();
                break;
            case 'POST':
                $this->handlePost();
                break;
            case 'PUT':
                $this->handlePut();
                break;
            case 'DELETE':
                $this->handleDelete();
                break;
            default:
                $this->jsonError("Méthode non autorisée", 405);
        }
    }
    
    private function handleGet(): void {
        if (isset($_GET['id'])) {
            $camping = $this->model->findById($_GET['id']);
            $camping ? $this->jsonSuccess($camping) : $this->jsonError("Camping non trouvé", 404);
        } else {
            $this->jsonSuccess($this->model->findAll());
        }
    }
    
    private function handlePost(): void {
        AuthMiddleware::requireAdmin();
        $data = $this->getJsonInput();
        
        if (empty($data['nom']) || empty($data['localisation']) || empty($data['capacite'])) {
            $this->jsonError("Données manquantes");
        }
        
        $result = $this->model->create(
            $data['nom'],
            $data['localisation'],
            $data['capacite'],
            $data['equipements'] ?? null
        );
        
        $result ? $this->jsonSuccess($result, 201) : $this->jsonError("Erreur création");
    }
    
    
    private function handlePut(): void {
        AuthMiddleware::requireAdmin();
        $data = $this->getJsonInput();
        
        if (empty($data['id'])) {
            $this->jsonError("ID manquant");
        }
        
        $result = $this->model->update(
            $data['id'],
            $data['nom'] ?? null,
            $data['localisation'] ?? null,
            $data['capacite'] ?? null,
            $data['equipements'] ?? null
        );
        
        $result ? $this->jsonSuccess($result) : $this->jsonError("Erreur mise à jour");
    }
    
    private function handleDelete(): void {
        AuthMiddleware::requireAdmin();
        $data = $this->getJsonInput();
        
        if (empty($data['id'])) {
            $this->jsonError("ID manquant");
        }
        
        $result = $this->model->delete($data['id']);
        $result ? $this->jsonSuccess(['deleted' => true]) : $this->jsonError("Erreur suppression");
    }
}

$controller = new CampingController();
$controller->handle();

