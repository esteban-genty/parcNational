<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../models/Database.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../utils/AuthMiddleware.php';
require_once __DIR__ . '/../../utils/ResponseHelper.php';

/**
 * API de gestion des utilisateurs (Admin seulement)
 * GET /api/admin/users - Liste tous les utilisateurs
 * DELETE /api/admin/users/{id} - Supprime un utilisateur
 */

try {
    $payload = AuthMiddleware::requireAdmin();
    
    if (!$payload) {
        exit(); // AuthMiddleware a déjà envoyé la réponse d'erreur
    }

    $database = new Database();
    $db = $database->getConnection();


    $method = $_SERVER['REQUEST_METHOD'];
    $user = new User($db);

    // GET /api/admin/users ou /api/admin/users?id=xx
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if (!$user->readOne($id)) {
                ResponseHelper::notFound("Utilisateur");
            }
            ResponseHelper::success([
                'id' => $user->id,
                'nom' => $user->nom,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ], "Utilisateur récupéré");
        } else {
            $query = "SELECT u.id, u.nom, u.email, u.role, u.created_at,
                             v.abonnement, v.carte_membre
                      FROM utilisateur u
                      LEFT JOIN visiteur v ON u.id = v.utilisateur_id
                      ORDER BY u.created_at DESC";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ResponseHelper::success($users, "Liste des utilisateurs récupérée");
        }
    }

    // POST /api/admin/users
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $user->nom = $data['nom'] ?? '';
        $user->email = $data['email'] ?? '';
        $user->mot_de_passe = $data['mot_de_passe'] ?? '';
        $user->role = $data['role'] ?? 'visiteur';
        $errors = $user->validate();
        if ($errors) {
            ResponseHelper::error(implode(', ', $errors), 400);
        }
        if ($user->emailExists($user->email)) {
            ResponseHelper::error("Email déjà utilisé", 409);
        }
        if ($user->create()) {
            ResponseHelper::success([
                'id' => $user->id,
                'nom' => $user->nom,
                'email' => $user->email,
                'role' => $user->role
            ], "Utilisateur créé");
        } else {
            ResponseHelper::serverError("Erreur lors de la création");
        }
    }

    // PUT /api/admin/users?id=xx
    elseif ($method === 'PUT') {
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            ResponseHelper::error("ID utilisateur invalide", 400);
        }
        if (!$user->readOne($id)) {
            ResponseHelper::notFound("Utilisateur");
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $user->nom = $data['nom'] ?? $user->nom;
        $user->email = $data['email'] ?? $user->email;
        $user->role = $data['role'] ?? $user->role;
        $errors = $user->validate();
        if ($errors) {
            ResponseHelper::error(implode(', ', $errors), 400);
        }
        if ($user->update()) {
            ResponseHelper::success([
                'id' => $user->id,
                'nom' => $user->nom,
                'email' => $user->email,
                'role' => $user->role
            ], "Utilisateur modifié");
        } else {
            ResponseHelper::serverError("Erreur lors de la modification");
        }
    }

    // DELETE /api/admin/users?id=xx
    elseif ($method === 'DELETE') {
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            ResponseHelper::error("ID utilisateur invalide", 400);
        }
        // Vérifier que l'admin ne se supprime pas lui-même
        if ($id == $payload['user_id']) {
            ResponseHelper::error("Vous ne pouvez pas supprimer votre propre compte", 400);
        }
        if (!$user->readOne($id)) {
            ResponseHelper::notFound("Utilisateur");
        }
        if ($user->delete()) {
            ResponseHelper::success(null, "Utilisateur supprimé avec succès");
        } else {
            ResponseHelper::serverError("Erreur lors de la suppression");
        }
    }

    else {
        ResponseHelper::methodNotAllowed();
    }

} catch (Exception $e) {
    error_log("Erreur admin/users: " . $e->getMessage());
    ResponseHelper::serverError("Erreur lors de la gestion des utilisateurs");
}
?>
