<?php
// 🌐 Configuration CORS centralisée
require_once __DIR__ . '/../../config/cors.php';

header('Content-Type: application/json');

// 🛡️ Gestion des erreurs pour retourner du JSON propre
try {
    require_once __DIR__ . '/../../models/Notification.php';
    require_once __DIR__ . '/../../models/Database.php';

    // 🗄️ Modèle Notification
    $model = new Notification();

    // 📍 Traitement des requêtes HTTP
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            try {
                // Si un utilisateur_id est fourni, filtrer les notifications
                if (isset($_GET['utilisateur_id'])) {
                    $notifications = $model->findByUserId($_GET['utilisateur_id']);
                    echo json_encode([
                        'success' => true,
                        'data' => is_array($notifications) ? $notifications : []
                    ]);
                } elseif (isset($_GET['id'])) {
                    $result = $model->findById($_GET['id']);
                    echo json_encode([
                        'success' => true,
                        'data' => $result ? $result : null
                    ]);
                } else {
                    $all = $model->findAll();
                    echo json_encode([
                        'success' => true,
                        'data' => is_array($all) ? $all : []
                    ]);
                }
            } catch (Exception $e) {
                // Si erreur, retourner tableau vide au lieu de crasher
                echo json_encode([
                    'success' => true,
                    'data' => [],
                    'message' => 'Aucune notification disponible'
                ]);
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $model->create($data);
            echo json_encode([
                'success' => true,
                'data' => $result
            ]);
            break;
            
        default:
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'error' => 'Méthode non autorisée'
            ]);
    }
    
} catch (Exception $e) {
    // 🚨 En cas d'erreur, retourner un JSON valide
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'data' => []
    ]);
}
