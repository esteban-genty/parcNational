<?php
/**
 * Script pour vérifier les données dans la BDD
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Database.php';

echo "=== VÉRIFICATION DES DONNÉES ===\n\n";

$db = (new Database())->connect();

// Campings
$stmt = $db->query('SELECT id, nom, localisation, capacite FROM camping ORDER BY id');
$campings = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "📍 CAMPINGS (" . count($campings) . " total)\n";
foreach ($campings as $c) {
    echo sprintf("  #%d - %s (%s) - %d places\n", $c['id'], $c['nom'], $c['localisation'] ?? 'N/A', $c['capacite'] ?? 0);
}
echo "\n";

// Sentiers
$stmt = $db->query('SELECT id, nom, difficulte, description FROM sentier ORDER BY id');
$sentiers = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "🥾 SENTIERS (" . count($sentiers) . " total)\n";
foreach ($sentiers as $s) {
    echo sprintf("  #%d - %s (%s)\n", $s['id'], $s['nom'], $s['difficulte'] ?? 'N/A');
}
echo "\n";

// Ressources Naturelles
$stmt = $db->query('SELECT id, nom, type, etat FROM ressource_naturelle ORDER BY type, id');
$ressources = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "🌿 RESSOURCES NATURELLES (" . count($ressources) . " total)\n";
$types = [];
foreach ($ressources as $r) {
    $type = $r['type'] ?? 'Inconnu';
    if (!isset($types[$type])) $types[$type] = 0;
    $types[$type]++;
}
foreach ($types as $type => $count) {
    echo "  - $type: $count\n";
}
echo "\n";

// Utilisateurs
$stmt = $db->query('SELECT id, nom, email, role FROM utilisateur ORDER BY role, id');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "👥 UTILISATEURS (" . count($users) . " total)\n";
foreach ($users as $u) {
    echo sprintf("  #%d - %s (%s) - Role: %s\n", $u['id'], $u['nom'], $u['email'], $u['role']);
}
echo "\n";

echo "=== RÉSUMÉ ===\n";
echo "✅ Campings: " . count($campings) . "\n";
echo "✅ Sentiers: " . count($sentiers) . "\n";
echo "✅ Ressources: " . count($ressources) . "\n";
echo "✅ Utilisateurs: " . count($users) . "\n";
?>
