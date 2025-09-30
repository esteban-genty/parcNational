<?php
// API Camping : gère les opérations CRUD sur les campings
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Camping.php';


$db = (new Database())->connect();
$model = new Camping($db);

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $camping = $model->findById($_GET['id']);
            if ($camping) {
                echo json_encode($camping);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Camping non trouvé']);
            }
        } else {
            echo json_encode($model->findAll());
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $created = $model->create($data);
        if ($created) {
            echo json_encode(['success' => true, 'camping' => $created]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erreur lors de la création']);
        }
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $updated = $model->update($data);
        if ($updated) {
            echo json_encode(['success' => true, 'camping' => $updated]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erreur lors de la modification']);
        }
        break;
    case 'DELETE':
        $id = $_GET['id'] ?? null;
        $deleted = $model->delete($id);
        if ($deleted) {
            echo json_encode(['success' => true, 'camping' => $deleted]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erreur lors de la suppression']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non autorisée']);
}
