<?php
// AuthController : gestion de l'authentification et du profil utilisateur (admin/visiteur)

require_once __DIR__ . '/../utils/BaseController.php';
require_once __DIR__ . '/../models/AuthModel.php';

// Le constructeur BaseController configure les CORS automatiquement
$baseController = new BaseController();

// Démarrer la session APRÈS les headers CORS
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class AuthController extends BaseController {

    // Enregistrement d'un utilisateur (admin ou visiteur)
    public function handleRequestRegister(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(["success" => false, "error" => "Seules les requêtes POST sont autorisées"]);
            exit();
        }
        $result = $this->registerUser();
        http_response_code($result['success'] ? 200 : 400);
        echo json_encode($result);
        exit();
    }

    // Connexion d'un utilisateur
    public function handleRequestLogin(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(["success" => false, "error" => "Seules les requêtes POST sont autorisées"]);
            exit();
        }
        $result = $this->loginUser();
        http_response_code($result['success'] ? 200 : 401);
        echo json_encode($result);
        exit();
    }

    // Logique métier de connexion
    private function loginUser(): array {
        $data = $this->getJsonInput();

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
        $data = $this->getJsonInput();

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
    public function logoutUser(): void {
        session_unset();
        session_destroy();
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Déconnexion réussie"]);
        exit();
    }

    // Vérification de session (profil utilisateur/admin)
    public function checkSession(): void {
        if (isset($_SESSION['user'])) {
            // Format spécial pour checkSession (pas enveloppé dans "data")
            http_response_code(200);
            echo json_encode(["loggedIn" => true, "user" => $_SESSION['user']]);
            exit();
        } else {
            http_response_code(200);
            echo json_encode(["loggedIn" => false]);
            exit();
        }
    }
}

// Le constructeur de BaseController configure automatiquement les CORS
$controller = new AuthController();
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'register':
        $controller->handleRequestRegister();
        break;
    case 'login':
        $controller->handleRequestLogin();
        break;
    case 'check':
        $controller->checkSession();
        break;
    case 'logout':
        $controller->logoutUser();
        break;
    default:
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Action non reconnue. Utilisez ?action=register, ?action=login, ?action=check ou ?action=logout'
        ]);
        break;
}

