<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once "../models/AuthModel.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Token JWT - more security : bin2hex(random_bytes(32))
$secret_key = "bin2hexrandombytes32";

// Configuration des en-têtes CORS
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");

class AuthController {

    private string $secret_key;

    public function __construct(string $secret_key) {
        $this->secret_key = $secret_key;
    }

    // Register
    public function handleRequestRegister(): array {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            return ["success" => true];
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ["success" => false, "error" => "Seules les requêtes POST sont autorisées"];
        }

        return $this->registerUser();
    }

    private function registerUser(): array {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data['nom'], $data['email'], $data['mot_de_passe'])) {
                throw new Exception("Données manquantes");
            }

            $nom = trim($data['nom']);
            $email = trim($data['email']);
            $motDePasse = $data['mot_de_passe'];
            $role = $data['role'] ?? "visiteur";

            htmlspecialchars($nom);
            htmlspecialchars($email);
            htmlspecialchars($motDePasse);

            $auth = new AuthModel();
            if ($auth->emailExists($email)) {
                return ["success" => false, "error" => "Cet email est déjà utilisé."];
            }

            $user = $auth->register($nom, $email, $motDePasse, $role);
            if (!$user) {
                return ["success" => false, "error" => "Impossible d'enregistrer l'utilisateur"];
            }

            $token = $this->generateJWT($user);

            return ["success" => true, "message" => "Inscription réussie", "token" => $token, "user" => $user];

        } catch (Exception $e) {
            http_response_code(500);
            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    // Connexion
    public function handleRequestLogin(): array {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            return ["success" => true];
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ["success" => false, "error" => "Seules les requêtes POST sont autorisées"];
        }

        return $this->loginUser();
    }

    private function loginUser(): array {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data['email'], $data['mot_de_passe'])) {
                throw new Exception("Données manquantes");
            }

            $email = trim($data['email']);
            $motDePasse = $data['mot_de_passe'];
            htmlspecialchars($email);
            htmlspecialchars($motDePasse);

            $auth = new AuthModel();
            $user = $auth->login($email, $motDePasse);
            if (!$user) {
                return ["success" => false, "error" => "Email ou mot de passe incorrect"];
            }

            $token = $this->generateJWT($user);

            return ["success" => true, "message" => "Connexion réussie", "token" => $token, "user" => $user];

        } catch (Exception $e) {
            http_response_code(500);
            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    // Check session need token
    public function checkSession(): array {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            return ["loggedIn" => false, "error" => "Token manquant"];
        }

        $authHeader = $headers['Authorization'];
        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return ["loggedIn" => false, "error" => "Format du token invalide"];
        }

        $token = $matches[1];

        try {
            $decoded = JWT::decode($token, new Key($this->secret_key, 'HS256'));
            return ["loggedIn" => true, "user" => (array) $decoded];
        } catch (Exception $e) {
            return ["loggedIn" => false, "error" => "Token invalide ou expiré"];
        }
    }

    // Lougout delete token ( React )
    public function logoutUser(): array {
        return ["success" => true, "message" => "Déconnexion réussie"];
    }

    // Generate JWT
    private function generateJWT(array $user): string {
        $payload = [
            "id" => $user['id'],
            "nom" => $user['nom'],
            "email" => $user['email'],
            "role" => $user['role'],
            "iat" => time(),
            "exp" => time() + 3600 // 1hour
        ];

        return JWT::encode($payload, $this->secret_key, 'HS256');
    }
}

// Router
$controller = new AuthController($secret_key);
$action = $_GET['action'] ?? '';

$response = match($action) {
    'register' => $controller->handleRequestRegister(),
    'login'    => $controller->handleRequestLogin(),
    'check'    => $controller->checkSession(),
    'logout'   => $controller->logoutUser(),
    default    => ["success" => false, "error" => "Action non reconnue. Utilisez ?action=register, ?action=login, ?action=check ou ?action=logout"]
};

echo json_encode($response);
