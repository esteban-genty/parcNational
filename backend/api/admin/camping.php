<?php
require_once '../../models/Camping.php';
require_once '../../models/Database.php';
require_once '../../utils/AuthMiddelware.php';

$db = (new Database())->connect();
AuthMiddleware::requireAdmin();
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
