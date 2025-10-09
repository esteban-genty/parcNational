<?php
/**
 * 🔐 Script pour créer un utilisateur de test
 */

require_once __DIR__ . '/../models/AuthModel.php';

echo "========================================\n";
echo "🔐 CRÉATION D'UTILISATEUR DE TEST\n";
echo "========================================\n\n";

try {
    $auth = new AuthModel();
    
    // Créer un utilisateur visiteur
    echo "📝 Création d'un visiteur de test...\n";
    $visiteur = $auth->register('Jean Dupont', 'user@test.fr', 'password123', 'visiteur');
    
    if ($visiteur) {
        echo "✅ Visiteur créé avec succès !\n";
        echo "   📧 Email: user@test.fr\n";
        echo "   🔑 Mot de passe: password123\n";
        echo "   👤 Rôle: visiteur\n\n";
    } else {
        echo "❌ Erreur : L'email existe déjà ou erreur de création\n\n";
    }
    
    // Créer un administrateur
    echo "📝 Création d'un administrateur...\n";
    $admin = $auth->register('Admin Parc', 'admin@test.fr', 'admin123', 'admin');
    
    if ($admin) {
        echo "✅ Admin créé avec succès !\n";
        echo "   📧 Email: admin@test.fr\n";
        echo "   🔑 Mot de passe: admin123\n";
        echo "   👤 Rôle: admin\n\n";
    } else {
        echo "❌ Erreur : L'email existe déjà ou erreur de création\n\n";
    }
    
    echo "========================================\n";
    echo "✨ Utilisez ces identifiants pour tester la connexion\n";
    echo "========================================\n";

} catch (Exception $e) {
    echo "\n❌ ERREUR: " . $e->getMessage() . "\n";
    exit(1);
}
?>
