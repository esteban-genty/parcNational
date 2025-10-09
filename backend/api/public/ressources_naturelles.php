<?php
require_once '../../models/RessourceNaturelle.php';
require_once '../../models/Database.php';
header('Content-Type: application/json');

$db = (new Database())->connect();
$model = new RessourceNaturelle($db);

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
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non autorisée']);
}
