<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../utils/AuthMiddleware.php';
require_once __DIR__ . '/../../utils/ResponseHelper.php';

/**
 * API de gestion des profils avec contrôle d'accès
 * GET /api/protected/profile-admin/{user_id} - Voir le profil d'un utilisateur
 * PUT /api/protected/profile-admin/{user_id} - Modifier le profil d'un utilisateur
 */

try {
    $path_parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    $user_id = end($path_parts);
    
    if (!is_numeric($user_id)) {
        ResponseHelper::error("ID utilisateur invalide", 400);
    }

    $payload = AuthMiddleware::requireOwnershipOrAdmin($user_id);
    
    if (!$payload) {
        exit(); // AuthMiddleware a déjà envoyé la réponse d'erreur
    }

    $database = new Database();
    $db = $database->getConnection();
    
    $user = new User($db);
    
    if (!$user->readOne($user_id)) {
        ResponseHelper::notFound("Utilisateur");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Récupération du profil
        $profileData = [
            'id' => $user->id,
            'nom' => $user->nom,
            'email' => $user->email,
            'role' => $user->role,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ];

        // Informations supplémentaires pour les admins
        if ($payload['role'] === 'admin') {
            $profileData['admin_info'] = [
                'can_edit' => true,
                'can_delete' => ($user->id != $payload['user_id']), // Admin ne peut pas se supprimer
                'last_login' => null // À implémenter si nécessaire
            ];
        }

        ResponseHelper::success($profileData, "Profil récupéré avec succès");

    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        // Mise à jour du profil
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            ResponseHelper::error("Données JSON invalides", 400);
        }

        // Seuls les admins peuvent modifier le rôle
        if (isset($input['role']) && $payload['role'] !== 'admin') {
            ResponseHelper::error("Seuls les administrateurs peuvent modifier les rôles", 403);
        }

        // Mise à jour des champs autorisés
        if (isset($input['nom'])) {
            $user->nom = $input['nom'];
        }
        
        if (isset($input['email'])) {
            $user->email = $input['email'];
        }
        
        if (isset($input['role']) && $payload['role'] === 'admin') {
            $user->role = $input['role'];
        }

        // Validation
        $errors = $user->validate();
        if (!empty($errors)) {
            ResponseHelper::validationError($errors);
        }

        // Mise à jour en base
        if (!$user->update()) {
            ResponseHelper::serverError("Erreur lors de la mise à jour du profil");
        }

        ResponseHelper::success(null, "Profil mis à jour avec succès");

    } else {
        ResponseHelper::methodNotAllowed();
    }

} catch (Exception $e) {
    error_log("Erreur protected/profile-admin: " . $e->getMessage());
    ResponseHelper::serverError("Erreur lors de la gestion du profil");
}
?>
