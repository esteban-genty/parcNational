<?php
// Script pour créer un compte administrateur
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/User.php';

$database = new Database();
$db = $database->getConnection();

// Créer l'utilisateur admin
$user = new User($db);
$user->nom = "Administrateur";
$user->email = "admin@parcnational.fr";
$user->mot_de_passe = "Admin123"; // Sera hashé dans create()
$user->role = "admin";

echo "Création du compte administrateur...\n";
$result = $user->create();

if ($result['success']) {
    echo "✓ Compte administrateur créé avec succès !\n";
    echo "  Email: admin@parcnational.fr\n";
    echo "  Mot de passe: Admin123\n";
    echo "  ID: " . $result['id'] . "\n";
} else {
    echo "✗ Erreur lors de la création: " . ($result['message'] ?? 'Erreur inconnue') . "\n";
}
?>
