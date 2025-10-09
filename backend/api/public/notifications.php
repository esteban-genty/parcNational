<?php
// 🌐 Configuration CORS pour permettre les requêtes depuis le frontend
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

// Gestion de la requête OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 🛡️ Gestion des erreurs pour retourner du JSON propre
try {
    require_once '../../models/Notification.php';
    require_once '../../models/Database.php';

    // 🗄️ Modèle Notification (pas besoin de passer $db, il étend Database)
    $model = new Notification();

    // 📍 Traitement des requêtes HTTP
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            // Si un utilisateur_id est fourni, filtrer les notifications
            if (isset($_GET['utilisateur_id'])) {
                $notifications = $model->findByUserId($_GET['utilisateur_id']);
                // Vérifier que c'est bien un tableau
                if (!is_array($notifications)) {
                    $notifications = [];
                }
                echo json_encode($notifications);
            } elseif (isset($_GET['id'])) {
                $result = $model->findById($_GET['id']);
                echo json_encode($result ? $result : []);
            } else {
                $all = $model->findAll();
                echo json_encode(is_array($all) ? $all : []);
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode($model->create($data));
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée']);
    }
    
} catch (Exception $e) {
    // 🚨 En cas d'erreur, retourner un JSON avec le message d'erreur
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage(),
        'data' => []
    ]);
}
