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

// Note: Les headers CORS sont maintenant gérés par config/cors.php (centralisé)
// Ne plus ajouter de headers ici pour éviter les conflits
?>
