<?php
/**
 * 🔔 Script pour créer des notifications de test
 * Ajoute quelques notifications dans la base de données
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Notification.php';

echo "========================================\n";
echo "🔔 CRÉATION DE NOTIFICATIONS DE TEST\n";
echo "========================================\n\n";

try {
    // 🗄️ Connexion à la base de données
    $database = new Database();
    $db = $database->connect();
    $notification = new Notification();

    // 📝 Notifications de test
    $notifications = [
        [
            'titre' => 'Bienvenue au Parc National',
            'message' => 'Merci de votre inscription ! Découvrez nos magnifiques calanques et sentiers.',
            'date_envoi' => date('Y-m-d')
        ],
        [
            'titre' => 'Nouvelle réservation disponible',
            'message' => 'Un emplacement de camping vient de se libérer pour le week-end prochain.',
            'date_envoi' => date('Y-m-d', strtotime('-1 day'))
        ],
        [
            'titre' => 'Maintenance programmée',
            'message' => 'Le sentier du Pic sera fermé le 15 octobre pour travaux d\'entretien.',
            'date_envoi' => date('Y-m-d', strtotime('-2 days'))
        ],
        [
            'titre' => 'Observation exceptionnelle',
            'message' => 'Des dauphins ont été aperçus dans la calanque de Sormiou ce matin !',
            'date_envoi' => date('Y-m-d', strtotime('-3 days'))
        ],
        [
            'titre' => 'Alerte météo',
            'message' => 'Prévisions de vents forts ce week-end. Nous recommandons la prudence sur les sentiers côtiers.',
            'date_envoi' => date('Y-m-d', strtotime('-5 days'))
        ]
    ];

    // 🔄 Création des notifications
    $count = 0;
    foreach ($notifications as $notif) {
        $result = $notification->create($notif);
        if ($result) {
            echo "✅ Notification créée: {$notif['titre']}\n";
            $count++;
        } else {
            echo "❌ Erreur pour: {$notif['titre']}\n";
        }
    }

    echo "\n========================================\n";
    echo "✨ $count notification(s) créée(s) avec succès !\n";
    echo "========================================\n";

} catch (Exception $e) {
    echo "\n❌ ERREUR: " . $e->getMessage() . "\n";
    exit(1);
}
?>
