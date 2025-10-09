<?php
/**
 * 🌐 Configuration CORS centralisée
 * 
 * Ce fichier gère les headers CORS pour toutes les API
 * - Autorise uniquement le frontend localhost:5173
 * - Permet l'envoi de credentials (JWT tokens, cookies)
 * - Gère les requêtes preflight (OPTIONS)
 */

// Origine autorisée (frontend Vite en développement)
$allowedOrigin = 'http://localhost:5173';

// Headers CORS
header("Access-Control-Allow-Origin: $allowedOrigin");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Gérer les requêtes OPTIONS (preflight CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
?>
