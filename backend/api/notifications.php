<?php
/**
 * API Notifications - Génération automatique basée sur les données réelles
 */

// 🌐 Configuration CORS centralisée
require_once __DIR__ . '/../config/cors.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../utils/BaseController.php';

class NotificationController extends BaseController {
    private Notification $model;
    
    public function __construct() {
        parent::__construct(); // Appel constructeur parent
        $this->model = new Notification();
    }
    
    public function handle(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $action = $_GET['action'] ?? 'list';
        
        switch ($action) {
            case 'list':
                $this->getActiveNotifications();
                break;
            case 'generate':
                $this->generateNotifications();
                break;
            case 'delete':
                $this->deleteNotification();
                break;
            default:
                $this->jsonError("Action inconnue", 400);
        }
    }
    
    private function getActiveNotifications(): void {
        try {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $notifications = $this->model->getActiveNotifications($limit);
            
            // Si la méthode n'existe pas ou retourne null, retourner tableau vide
            if (!is_array($notifications)) {
                $notifications = [];
            }
            
            $this->jsonSuccess($notifications);
        } catch (Exception $e) {
            // En cas d'erreur, retourner tableau vide au lieu de crasher
            $this->jsonSuccess([]);
        }
    }
    
    private function generateNotifications(): void {
        // Génère automatiquement les notifications basées sur les données réelles
        $generated = [];
        
        // Vérifier les sentiers dangereux
        $sentiersNotifs = $this->model->checkDangerousSentiers();
        $generated['sentiers'] = count($sentiersNotifs);
        
        // Vérifier la capacité des campings
        $campingNotifs = $this->model->checkCampingCapacity();
        $generated['campings'] = count($campingNotifs);
        
        $this->jsonSuccess([
            'message' => 'Notifications générées avec succès',
            'generated' => $generated,
            'total' => array_sum($generated)
        ]);
    }
    
    private function deleteNotification(): void {
        $data = $this->getJsonInput();
        
        if (empty($data['id'])) {
            $this->jsonError("ID manquant");
        }
        
        $result = $this->model->delete($data['id']);
        $result ? $this->jsonSuccess(['deleted' => true]) : $this->jsonError("Erreur suppression");
    }
}

$controller = new NotificationController();
$controller->handle();
