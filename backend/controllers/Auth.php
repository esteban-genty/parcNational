<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuration des en-têtes CORS
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");


require_once "../models/AuthModel.php";

class AuthController {

    public function handleRequestRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                "success" => false,
                "error" => "Seules les requêtes POST sont autorisées"
            ]);
            exit();
        }

        $this->registerUser();
    }

    public function handleRequestLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                "success" => false,
                "error" => "Seules les requêtes POST sont autorisées"
            ]);
            exit();
        }

        $this->loginUser();
    }

    private function loginUser() {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data['email'], $data['mot_de_passe'])) {
                throw new Exception("Données manquantes");
            }

            $email = trim($data['email']);
            $motDePasse = $data['mot_de_passe'];

            $login = new AuthModel();
            $result = $login->login($email, $motDePasse);

            if ($result) {

                //session_start();

                //$_SESSION['user'] = $email;


                echo json_encode(["success" => true, "message" => "Connexion réussie"]);
            } else {
                echo json_encode(["success" => false, "error" => "Email ou mot de passe incorrect"]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    }

    private function registerUser() {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data['nom'], $data['email'], $data['mot_de_passe'])) {
                throw new Exception("Données manquantes");
            }

            $nom = trim($data['nom']);
            $email = trim($data['email']);
            $motDePasse = $data['mot_de_passe'];
            $role = $data['role'] ?? "visiteur";

            $signin = new AuthModel();
            $result = $signin->register($nom, $email, $motDePasse, $role);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Inscription réussie"]);
            } else {
                echo json_encode(["success" => false, "error" => "Impossible d'enregistrer l'utilisateur"]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    }
}


$controller = new AuthController();

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'register':
        $controller->handleRequestRegister();
        break;

    case 'login':
        $controller->handleRequestLogin();
        break;

    default:
        echo json_encode([
            "success" => false,
            "error" => "Action non reconnue. Utilisez ?action=register ou ?action=login"
        ]);
        break;
}
