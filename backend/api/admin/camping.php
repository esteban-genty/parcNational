<?php
// 🌐 Configuration CORS pour permettre les requêtes depuis le frontend
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

// Gestion de la requête OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../../models/Camping.php';
require_once '../../models/Database.php';
require_once '../../utils/AuthMiddelware.php';

// 🔐 Vérification que l'utilisateur est admin (via session ou JWT)
$user = AuthMiddleware::requireAdmin();
if (!$user) {
    // L'erreur est déjà envoyée par le middleware
    exit();
}

// 🗄️ Connexion à la base de données
$db = (new Database())->connect();
$model = new Camping($db);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id'])) {
            echo json_encode($model->findById($_GET['id']));
        } else {
            echo json_encode($model->findAll());
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($model->create($data));
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($model->update($data));
        break;
    case 'DELETE':
        $id = $_GET['id'] ?? null;
        echo json_encode($model->delete($id));
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non autorisée']);
}
