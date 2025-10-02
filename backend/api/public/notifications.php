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

require_once '../../models/Notification.php';
require_once '../../models/Database.php';

// 🗄️ Connexion à la base de données
$db = (new Database())->connect();
$model = new Notification($db);

// 📍 Traitement des requêtes HTTP
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Si un utilisateur_id est fourni, filtrer les notifications
        if (isset($_GET['utilisateur_id'])) {
            $notifications = $model->findByUserId($_GET['utilisateur_id']);
            echo json_encode($notifications);
        } elseif (isset($_GET['id'])) {
            echo json_encode($model->findById($_GET['id']));
        } else {
            echo json_encode($model->findAll());
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
