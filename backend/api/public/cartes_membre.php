<?php
// CarteMembre model file

class CarteMembre {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findById($id) {
        // Implement findById logic
    }

    public function findAll() {
        // Implement findAll logic
    }

    public function create($data) {
        // Implement create logic
    }
}

require_once '../../models/Database.php';
header('Content-Type: application/json');

$db = (new Database())->connect();
$model = new CarteMembre($db);

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
