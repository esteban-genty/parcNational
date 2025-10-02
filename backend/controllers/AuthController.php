<?php
// AuthController : gestion de l'authentification et du profil utilisateur (admin/visiteur)

// Gestion CORS (DOIT être en premier, avant session_start)
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// Gestion des requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/AuthModel.php';

class AuthController {

    // Enregistrement d'un utilisateur (admin ou visiteur)
    public function handleRequestRegister(): array {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ["success" => false, "error" => "Seules les requêtes POST sont autorisées"];
        }
        return $this->registerUser();
    }

    // Connexion d'un utilisateur
    public function handleRequestLogin(): array {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ["success" => false, "error" => "Seules les requêtes POST sont autorisées"];
        }
        return $this->loginUser();
    }

    // Logique métier de connexion
    private function loginUser(): array {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['email'], $data['mot_de_passe'])) {
            return ["success" => false, "error" => "Données manquantes"];
        }
        $email = htmlspecialchars(trim($data['email']));
        $motDePasse = htmlspecialchars($data['mot_de_passe']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["success" => false, "error" => "Email invalide"];
        }
        $auth = new AuthModel();
        $result = $auth->login($email, $motDePasse);
        if ($result) {
            $_SESSION['user'] = [
                "id"    => $result['id'],
                "nom"   => $result['nom'],
                "email" => $result['email'],
                "role"  => $result['role']
            ];
            return ["success" => true, "user" => $_SESSION['user']];
        }
        return ["success" => false, "error" => "Email ou mot de passe incorrect"];
    }

    // Logique métier d'enregistrement
    private function registerUser(): array {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['nom'], $data['email'], $data['mot_de_passe'])) {
            return ["success" => false, "error" => "Données manquantes"];
        }
        $nom = htmlspecialchars(trim($data['nom']));
        $email = htmlspecialchars(trim($data['email']));
        $motDePasse = htmlspecialchars($data['mot_de_passe']);
        $role = htmlspecialchars($data['role'] ?? "visiteur");
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["success" => false, "error" => "Email invalide"];
        }
        $auth = new AuthModel();
        if ($auth->emailExists($email)) {
            return ["success" => false, "error" => "Cet email est déjà utilisé."];
        }
        $user = $auth->register($nom, $email, $motDePasse, $role);
        if ($user) {
            $_SESSION['user'] = [
                "id"    => $user['id'],
                "nom"   => $user['nom'],
                "email" => $user['email'],
                "role"  => $user['role']
            ];
            return ["success" => true, "user" => $_SESSION['user']];
        }
        return ["success" => false, "error" => "Impossible d'enregistrer l'utilisateur"];
    }

    // Déconnexion
    public function logoutUser(): array {
        session_unset();
        session_destroy();
        return ["success" => true, "message" => "Déconnexion réussie"];
    }

    // Vérification de session (profil utilisateur/admin)
    public function checkSession(): array {
        if (isset($_SESSION['user'])) {
            return ["loggedIn" => true, "user" => $_SESSION['user']];
        }
        return ["loggedIn" => false];
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
