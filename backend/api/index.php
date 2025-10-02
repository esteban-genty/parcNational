<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/ResponseHelper.php';

/**
 * Point d'entrée principal de l'API du Parc National des Calanques
 */

// Routage simple basé sur l'URL
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Suppression du préfixe /api si présent
$path = preg_replace('#^/api#', '', $path);

// Routes disponibles
switch ($path) {
    case '/auth/register':
        require_once __DIR__ . '/auth/register.php';
        break;
        
    case '/auth/login':
        require_once __DIR__ . '/auth/login.php';
        break;
        
    case '/auth/refresh':
        require_once __DIR__ . '/auth/refresh.php';
        break;
        
    case '/auth/profile':
        require_once __DIR__ . '/auth/profile.php';
        break;
        
    case '/auth/logout':
        require_once __DIR__ . '/auth/logout.php';
        break;
        
    case '/':
    case '':
        ResponseHelper::success([
            'app' => APP_NAME,
            'version' => APP_VERSION,
            'endpoints' => [
                'POST /api/auth/register' => 'Inscription',
                'POST /api/auth/login' => 'Connexion',
                'POST /api/auth/refresh' => 'Rafraîchir le token',
                'GET /api/auth/profile' => 'Récupérer le profil',
                'PUT /api/auth/profile' => 'Mettre à jour le profil',
                'POST /api/auth/logout' => 'Déconnexion'
            ]
        ], "API du Parc National des Calanques");
        break;
        
    default:
        ResponseHelper::notFound("Endpoint");
        break;
}
?>
