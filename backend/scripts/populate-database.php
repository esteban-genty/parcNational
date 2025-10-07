<?php
/**
 * Script pour peupler la base de données avec des données réelles
 * Campings, Sentiers et Ressources Naturelles du Parc National des Calanques
 */

require_once __DIR__ . '/../models/Database.php';

$db = new Database();
$conn = $db->connect();

echo "========================================\n";
echo "🏞️ PEUPLEMENT BASE DE DONNÉES\n";
echo "========================================\n\n";

// ============================================
// 🏕️ CAMPINGS
// ============================================
echo "📍 Ajout des campings...\n";

$campings = [
    ['Camping des Calanques', 'Marseille - Callelongue', 50],
    ['Camping Sormiou', 'Calanque de Sormiou', 30],
    ['Camping Morgiou', 'Calanque de Morgiou', 25],
    ['Camping En Vau', 'Calanque d\'En Vau', 20],
    ['Camping Port-Miou', 'Calanque de Port-Miou', 40],
    ['Camping Sugiton', 'Calanque de Sugiton', 35],
    ['Camping Les Goudes', 'Les Goudes', 45]
];

$stmt = $conn->prepare("INSERT INTO camping (nom, localisation, capacite) VALUES (?, ?, ?)");

$count_camping = 0;
foreach ($campings as $camping) {
    try {
        $stmt->execute($camping);
        $count_camping++;
        echo "  ✅ {$camping[0]} ({$camping[2]} places)\n";
    } catch (PDOException $e) {
        if ($e->getCode() != 23000) { // Ignore les doublons
            echo "  ⚠️  Erreur: " . $e->getMessage() . "\n";
        }
    }
}
echo "✅ Total: $count_camping campings ajoutés\n\n";

// ============================================
// 🥾 SENTIERS
// ============================================
echo "📍 Ajout des sentiers...\n";

$sentiers = [
    ['GR51 - Marseille à Cassis', 'facile', 'Sentier côtier avec vues panoramiques sur les calanques'],
    ['Calanque de Sormiou', 'moyen', 'Descente vers la calanque, retour en montée raide'],
    ['Calanque d\'En Vau', 'difficile', 'Sentier escarpé avec passages rocheux techniques'],
    ['Col de Sugiton', 'moyen', 'Sentier forestier puis rocheux jusqu\'au col'],
    ['Belvédère de Sugiton', 'facile', 'Point de vue exceptionnel sur la calanque'],
    ['Calanque de Morgiou', 'moyen', 'Sentier varié avec passages en forêt de pins'],
    ['Pas de la Demi-Lune', 'difficile', 'Passage technique avec crête exposée'],
    ['Calanque de Port-Pin', 'facile', 'Sentier ombragé avec accès facile à la plage'],
    ['Mont Puget (565m)', 'difficile', 'Plus haut sommet du massif avec dénivelé important'],
    ['Sentier du Président', 'moyen', 'Entre Port-Miou et Port-Pin, sentier historique']
];

$stmt = $conn->prepare("INSERT INTO sentier (nom, difficulte, description) VALUES (?, ?, ?)");

$count_sentier = 0;
foreach ($sentiers as $sentier) {
    try {
        $stmt->execute($sentier);
        $count_sentier++;
        echo "  ✅ {$sentier[0]} - {$sentier[1]}\n";
    } catch (PDOException $e) {
        if ($e->getCode() != 23000) {
            echo "  ⚠️  Erreur: " . $e->getMessage() . "\n";
        }
    }
}
echo "✅ Total: $count_sentier sentiers ajoutés\n\n";

// ============================================
// 🌿 RESSOURCES NATURELLES
// ============================================
echo "📍 Ajout des ressources naturelles...\n";

$ressources = [
    // Flore
    ['Flore', 'Pin d\'Alep', 'Bon'],
    ['Flore', 'Chêne kermès', 'Bon'],
    ['Flore', 'Thym sauvage', 'Excellent'],
    ['Flore', 'Romarin officinal', 'Excellent'],
    ['Flore', 'Ciste blanc', 'Bon'],
    ['Flore', 'Genévrier de Phénicie', 'Fragile'],
    ['Flore', 'Pistachier lentisque', 'Bon'],
    ['Flore', 'Sabline de Marseille', 'En danger'],
    
    // Faune terrestre
    ['Faune', 'Lézard ocellé', 'Vulnérable'],
    ['Faune', 'Faucon pèlerin', 'Bon'],
    ['Faune', 'Goéland leucophée', 'Bon'],
    ['Faune', 'Cormoran huppé', 'Bon'],
    ['Faune', 'Sanglier', 'Bon'],
    ['Faune', 'Renard roux', 'Bon'],
    
    // Faune marine
    ['Marine', 'Posidonie', 'Protégée'],
    ['Marine', 'Mérou brun', 'En récupération'],
    ['Marine', 'Corail rouge', 'Protégé'],
    ['Marine', 'Grande nacre', 'En danger critique'],
    ['Marine', 'Daurade royale', 'Bon'],
    ['Marine', 'Poulpe commun', 'Bon'],
    ['Marine', 'Oursin violet', 'Bon'],
    
    // Géologie
    ['Géologie', 'Calcaire urgonien', 'Bon'],
    ['Géologie', 'Grotte Cosquer', 'Fragile'],
    ['Géologie', 'Arches naturelles', 'Bon']
];

$stmt = $conn->prepare("INSERT INTO ressource_naturelle (type, nom, etat) VALUES (?, ?, ?)");

$count_ressource = 0;
foreach ($ressources as $ressource) {
    try {
        $stmt->execute($ressource);
        $count_ressource++;
        echo "  ✅ {$ressource[0]}: {$ressource[1]}\n";
    } catch (PDOException $e) {
        if ($e->getCode() != 23000) {
            echo "  ⚠️  Erreur: " . $e->getMessage() . "\n";
        }
    }
}
echo "✅ Total: $count_ressource ressources ajoutées\n\n";

// ============================================
// 📊 RÉSUMÉ
// ============================================
echo "========================================\n";
echo "📊 RÉSUMÉ\n";
echo "========================================\n";
echo "🏕️  Campings:            $count_camping\n";
echo "🥾 Sentiers:            $count_sentier\n";
echo "🌿 Ressources:          $count_ressource\n";
echo "========================================\n";
echo "✅ Base de données peuplée avec succès !\n";
echo "========================================\n";
?>
