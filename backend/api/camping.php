<?php
// API Camping : gère les opérations CRUD sur les campings
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Camping.php';

$db = new Database();
$camping = new Camping($db);

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Lire un camping
            if ($camping->read($_GET['id'])) {
                echo json_encode($camping);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Camping non trouvé']);
            }
        } else {
            // Lire tous les campings
            echo json_encode($camping->readAll());
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $camping->nom = $data['nom'] ?? '';
        $camping->localisation = $data['localisation'] ?? '';
        $camping->capacite = $data['capacite'] ?? 0;
        if ($camping->create()) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erreur lors de la création']);
        }
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $camping->id = $data['id'] ?? null;
        $camping->nom = $data['nom'] ?? '';
        $camping->localisation = $data['localisation'] ?? '';
        $camping->capacite = $data['capacite'] ?? 0;
        if ($camping->update()) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erreur lors de la modification']);
        }
        break;
    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if ($id && $camping->delete($id)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erreur lors de la suppression']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non autorisée']);
}
