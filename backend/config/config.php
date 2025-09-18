<?php
/**
 * Configuration générale de l'application
 */

// Configuration JWT
define('JWT_SECRET_KEY', 'votre_cle_secrete_jwt_tres_longue_et_complexe_2024');
define('JWT_ALGORITHM', 'HS256');
define('JWT_EXPIRATION_TIME', 3600); // 1 heure en secondes

// Configuration de l'application
define('APP_NAME', 'Parc National des Calanques');
define('APP_VERSION', '1.0.0');

// Configuration des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Headers CORS pour les API
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Gestion des requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
?>
