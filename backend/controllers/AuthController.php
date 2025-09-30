<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuration des en-têtes CORS

// Gestion CORS globale
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Credentials: true");
    http_response_code(200);
    exit;
}
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");

require_once "../models/AuthModel.php";

class AuthController {

    public function handleRequestRegister(): array {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            return ["success" => true];
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return [
                "success" => false,
                "error"   => "Seules les requêtes POST sont autorisées"
            ];
        }

        return $this->registerUser();
    }

    public function handleRequestLogin(): array {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            return ["success" => true];
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return [
                "success" => false,
                "error"   => "Seules les requêtes POST sont autorisées"
            ];
        }

        return $this->loginUser();
    }

    private function loginUser(): array {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data['email'], $data['mot_de_passe'])) {
                throw new Exception("Données manquantes");
            }

            $email = htmlspecialchars(trim($data['email']));
            $motDePasse = htmlspecialchars($data['mot_de_passe']);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                return ["success" => false, "error" => "Email invalide"];
            }

            $login = new AuthModel();
            $result = $login->login($email, $motDePasse);

            if ($result) {
                $_SESSION['user'] = [
                    "id"    => $result['id'],
                    "nom"   => $result['nom'],
                    "email" => $result['email'],
                    "role"  => $result['role']
                ];
                http_response_code(200);
                return [
                    "success" => true,
                    "message" => "Connexion réussie, {$_SESSION['user']['nom']}",
                    "user"    => $_SESSION['user']
                ];
            } else {
                http_response_code(401);
                return ["success" => false, "error" => "Email ou mot de passe incorrect"];
            }
        } catch (Exception $e) {
            http_response_code(500);
            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    private function registerUser(): array {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data['nom'], $data['email'], $data['mot_de_passe'])) {
                throw new Exception("Données manquantes");
            }

            $nom = htmlspecialchars(trim($data['nom']));
            $email = htmlspecialchars(trim($data['email']));
            $motDePasse = htmlspecialchars($data['mot_de_passe']);
            $role = htmlspecialchars($data['role'] ?? "visiteur");

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                return ["success" => false, "error" => "Email invalide"];
            }

            $signin = new AuthModel();

            if ($signin->emailExists($email)) {
                http_response_code(409);
                return [
                    "success" => false,
                    "error"   => "Cet email est déjà utilisé."
                ];
            }

            $user = $signin->register($nom, $email, $motDePasse, $role);

            if ($user) {
                $_SESSION['user'] = [
                    "id"    => $user['id'],
                    "nom"   => $user['nom'],
                    "email" => $user['email'],
                    "role"  => $user['role']
                ];
                http_response_code(201);
                return [
                    "success" => true,
                    "message" => "Inscription réussie",
                    "user"    => $_SESSION['user']
                ];
            } else {
                http_response_code(500);
                return [
                    "success" => false,
                    "error"   => "Impossible d'enregistrer l'utilisateur"
                ];
            }

        } catch (Exception $e) {
            http_response_code(500);
            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    public function logoutUser(): array {
        session_unset();
        session_destroy();
        return [
            "success" => true,
            "message" => "Déconnexion réussie"
        ];
    }

    public function checkSession(): array {
        if (isset($_SESSION['user'])) {
            return [
                "loggedIn" => true,
                "user"     => $_SESSION['user']
            ];
        } else {
            return [
                "loggedIn" => false
            ];
        }
    }
}


$controller = new AuthController();
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'register':
        $response = $controller->handleRequestRegister();
        break;
    case 'login':
        $response = $controller->handleRequestLogin();
        break;
    case 'check':
        $response = $controller->checkSession();
        break;
    case 'logout':
        $response = $controller->logoutUser();
        break;
    default:
        $response = [
            "success" => false,
            "error"   => "Action non reconnue. Utilisez ?action=register, ?action=login, ?action=check ou ?action=logout"
        ];
        break;
}


echo json_encode($response);
