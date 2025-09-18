<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Visiteur.php';
require_once __DIR__ . '/../../utils/AuthMiddleware.php';
require_once __DIR__ . '/../../utils/ResponseHelper.php';

/**
 * API de gestion du profil utilisateur
 * GET /api/auth/profile - Récupérer le profil
 * PUT /api/auth/profile - Mettre à jour le profil
 */

try {
    // Vérification de l'authentification
    $currentUser = AuthMiddleware::getCurrentUser();
    
    if (!$currentUser) {
        ResponseHelper::error("Utilisateur non authentifié", 401, "UNAUTHORIZED");
    }

    $database = new Database();
    $db = $database->getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Récupération du profil
        $profileData = [
            'id' => $currentUser->id,
            'nom' => $currentUser->nom,
            'email' => $currentUser->email,
            'role' => $currentUser->role,
            'created_at' => $currentUser->created_at
        ];

        // Si c'est un visiteur, récupérer les informations supplémentaires
        if ($currentUser->role === 'visiteur') {
            $visiteur = new Visiteur($db);
            if ($visiteur->readByUserId($currentUser->id)) {
                $profileData['visiteur'] = [
                    'abonnement' => $visiteur->abonnement,
                    'carte_membre' => $visiteur->carte_membre
                ];
            }
        }

        ResponseHelper::success($profileData, "Profil récupéré avec succès");

    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        // Mise à jour du profil
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            ResponseHelper::error("Données JSON invalides", 400);
        }

        // Mise à jour des champs autorisés
        if (isset($input['nom'])) {
            $currentUser->nom = $input['nom'];
        }
        
        if (isset($input['email'])) {
            // Vérifier que l'email n'est pas déjà utilisé par un autre utilisateur
            $tempUser = new User($db);
            if ($tempUser->emailExists($input['email']) && $tempUser->id != $currentUser->id) {
                ResponseHelper::error("Cet email est déjà utilisé", 409, "EMAIL_EXISTS");
            }
            $currentUser->email = $input['email'];
        }

        // Validation
        $errors = $currentUser->validate();
        if (!empty($errors)) {
            ResponseHelper::validationError($errors);
        }

        // Mise à jour en base
        if (!$currentUser->update()) {
            ResponseHelper::serverError("Erreur lors de la mise à jour du profil");
        }

        // Mise à jour du profil visiteur si applicable
        if ($currentUser->role === 'visiteur' && (isset($input['abonnement']) || isset($input['carte_membre']))) {
            $visiteur = new Visiteur($db);
            if ($visiteur->readByUserId($currentUser->id)) {
                if (isset($input['abonnement'])) {
                    $visiteur->abonnement = $input['abonnement'];
                }
                if (isset($input['carte_membre'])) {
                    $visiteur->carte_membre = $input['carte_membre'];
                }
                $visiteur->update();
            }
        }

        ResponseHelper::success(null, "Profil mis à jour avec succès");

    } else {
        ResponseHelper::methodNotAllowed();
    }

} catch (Exception $e) {
    error_log("Erreur profil: " . $e->getMessage());
    ResponseHelper::serverError("Erreur lors de la gestion du profil");
}
?>
