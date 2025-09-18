<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Visiteur.php';
require_once __DIR__ . '/../../utils/Validator.php';
require_once __DIR__ . '/../../utils/ResponseHelper.php';
require_once __DIR__ . '/../../utils/JWTHandler.php';

/**
 * API d'inscription pour le Parc National des Calanques
 * POST /api/auth/register
 */

// Vérification de la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ResponseHelper::methodNotAllowed();
}

try {
    // Récupération des données JSON
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        ResponseHelper::error("Données JSON invalides", 400);
    }

    // Validation des données d'entrée
    $validationErrors = Validator::validateRegistration($input);
    
    if (!empty($validationErrors)) {
        ResponseHelper::validationError($validationErrors);
    }

    // Connexion à la base de données
    $database = new Database();
    $db = $database->getConnection();

    // Création de l'objet User
    $user = new User($db);
    
    // Vérification si l'email existe déjà
    if ($user->emailExists($input['email'])) {
        ResponseHelper::error("Cet email est déjà utilisé", 409, "EMAIL_EXISTS");
    }

    // Attribution des valeurs
    $user->nom = $input['nom'];
    $user->email = $input['email'];
    $user->mot_de_passe = $input['mot_de_passe'];
    $user->role = isset($input['role']) ? $input['role'] : 'visiteur';

    // Validation finale
    $errors = $user->validate();
    if (!empty($errors)) {
        ResponseHelper::validationError($errors);
    }

    // Début de transaction
    $db->beginTransaction();

    try {
        // Création de l'utilisateur
        if (!$user->create()) {
            throw new Exception("Erreur lors de la création de l'utilisateur");
        }

        // Si c'est un visiteur, créer le profil visiteur
        if ($user->role === 'visiteur') {
            $visiteur = new Visiteur($db);
            $visiteur->utilisateur_id = $user->id;
            $visiteur->abonnement = isset($input['abonnement']) ? $input['abonnement'] : null;
            $visiteur->carte_membre = isset($input['carte_membre']) ? $input['carte_membre'] : null;
            
            if (!$visiteur->create()) {
                throw new Exception("Erreur lors de la création du profil visiteur");
            }
        }

        // Génération du token JWT
        $payload = JWTHandler::createUserPayload($user);
        $token = JWTHandler::generateToken($payload);
        $refreshToken = JWTHandler::generateRefreshToken($user->id);

        // Validation de la transaction
        $db->commit();

        // Réponse de succès
        ResponseHelper::created([
            'user' => [
                'id' => $user->id,
                'nom' => $user->nom,
                'email' => $user->email,
                'role' => $user->role
            ],
            'tokens' => [
                'access_token' => $token,
                'refresh_token' => $refreshToken,
                'expires_in' => JWT_EXPIRATION_TIME
            ]
        ], "Inscription réussie");

    } catch (Exception $e) {
        // Annulation de la transaction en cas d'erreur
        $db->rollback();
        throw $e;
    }

} catch (Exception $e) {
    error_log("Erreur inscription: " . $e->getMessage());
    ResponseHelper::serverError("Erreur lors de l'inscription");
}
?>
