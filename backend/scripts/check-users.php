<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Database.php';

$db = new Database();
$conn = $db->connect();

echo "=== UTILISATEURS DANS LA BASE DE DONNÉES ===\n\n";

$stmt = $conn->query('SELECT id, nom, email, role FROM utilisateur ORDER BY id');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($users)) {
    echo "❌ Aucun utilisateur trouvé dans la base de données!\n";
} else {
    foreach ($users as $user) {
        echo sprintf(
            "ID: %-3d | Nom: %-20s | Email: %-30s | Role: %s\n",
            $user['id'],
            $user['nom'],
            $user['email'],
            $user['role']
        );
    }
    echo "\n✅ Total: " . count($users) . " utilisateurs\n";
}

// Vérifier spécifiquement les visiteurs
echo "\n=== VISITEURS UNIQUEMENT ===\n\n";
$stmt = $conn->prepare('SELECT id, nom, email FROM utilisateur WHERE role = :role');
$stmt->execute([':role' => 'visiteur']);
$visiteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($visiteurs)) {
    echo "⚠️  Aucun visiteur trouvé - CECI POURRAIT ÊTRE LE PROBLÈME!\n";
} else {
    foreach ($visiteurs as $visiteur) {
        echo sprintf(
            "ID: %-3d | Nom: %-20s | Email: %s\n",
            $visiteur['id'],
            $visiteur['nom'],
            $visiteur['email']
        );
    }
    echo "\n✅ Total: " . count($visiteurs) . " visiteurs\n";
}
