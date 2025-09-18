<?php
/**
 * Bootstrap pour les tests PHPUnit
 */

// Autoloader simple pour les tests
spl_autoload_register(function ($class) {
    $directories = [
        __DIR__ . '/../models/',
        __DIR__ . '/../utils/',
        __DIR__ . '/../config/',
        __DIR__ . '/Helpers/'
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Configuration des constantes pour les tests
if (!defined('JWT_SECRET_KEY')) {
    define('JWT_SECRET_KEY', 'test_secret_key_for_phpunit_tests_only');
}
if (!defined('JWT_ALGORITHM')) {
    define('JWT_ALGORITHM', 'HS256');
}
if (!defined('JWT_EXPIRATION_TIME')) {
    define('JWT_EXPIRATION_TIME', 3600);
}
if (!defined('APP_NAME')) {
    define('APP_NAME', 'Parc National Test');
}

// Désactiver l'affichage des erreurs pour les tests
error_reporting(0);
ini_set('display_errors', 0);
?>
