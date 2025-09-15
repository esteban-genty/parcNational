<?php
require_once "database.php";
require_once "auth.php";

// Test connexion BDD
try {
    $db = new Database();
    $conn = $db->connect();
    echo "Connexion à la BDD réussie.<br>";
} catch (Exception $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$auth = new Auth($db);

// Test inscription
$nom = "TestUser";
$email = "testuser@example.com";
$mot_de_passe = "password123";
$role = "visiteur";

if ($auth->register($nom, $email, $mot_de_passe, $role)) {
    echo "Inscription réussie.<br>";
} else {
    echo "Erreur lors de l'inscription.<br>";
}

// Test connexion
if ($auth->login($email, $mot_de_passe)) {
    echo "Connexion utilisateur réussie.<br>";
} else {
    echo "Échec de la connexion utilisateur.<br>";
}
