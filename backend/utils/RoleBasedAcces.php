<?php
require_once __DIR__ . '/AuthMiddleware.php';

/**
 * Classe utilitaire pour la gestion des accès basés sur les rôles
 */
class RoleBasedAccess {
    
    /**
     * Définition des permissions par rôle
     */
    private static $permissions = [
        'admin' => [
            'users.create',
            'users.read',
            'users.update',
            'users.delete',
            'campings.create',
            'campings.read',
            'campings.update',
            'campings.delete',
            'sentiers.create',
            'sentiers.read',
            'sentiers.update',
            'sentiers.delete',
            'reservations.read_all',
            'reservations.manage',
            'resources.manage',
            'notifications.send'
        ],
        'visiteur' => [
            'sentiers.read',
            'campings.read',
            'reservations.create',
            'reservations.read_own',
            'reservations.update_own',
            'profile.read_own',
            'profile.update_own'
        ]
    ];

    /**
     * Vérifie si l'utilisateur a une permission spécifique
     * @param string $permission
     * @return bool
     */
    public static function hasPermission($permission) {
        $payload = AuthMiddleware::authenticate();
        
        if (!$payload) {
            return false;
        }

        $userRole = $payload['role'];
        
        return isset(self::$permissions[$userRole]) && 
               in_array($permission, self::$permissions[$userRole]);
    }

    /**
     * Middleware pour vérifier une permission spécifique
     * @param string $permission
     * @return array|false
     */
    public static function requirePermission($permission) {
        if (!self::hasPermission($permission)) {
            ResponseHelper::error("Permission insuffisante", 403, "INSUFFICIENT_PERMISSION");
            return false;
        }

        return AuthMiddleware::authenticate();
    }

    /**
     * Vérifie si l'utilisateur peut accéder à une ressource
     * @param string $resource
     * @param string $action
     * @param int|null $resourceOwnerId
     * @return bool
     */
    public static function canAccess($resource, $action, $resourceOwnerId = null) {
        $payload = AuthMiddleware::authenticate();
        
        if (!$payload) {
            return false;
        }

        $permission = $resource . '.' . $action;
        
        // Vérification de la permission de base
        if (self::hasPermission($permission)) {
            return true;
        }

        // Vérification de la permission "own" (ses propres ressources)
        if ($resourceOwnerId && $payload['user_id'] == $resourceOwnerId) {
            $ownPermission = $resource . '.' . $action . '_own';
            return self::hasPermission($ownPermission);
        }

        return false;
    }

    /**
     * Retourne toutes les permissions d'un rôle
     * @param string $role
     * @return array
     */
    public static function getRolePermissions($role) {
        return isset(self::$permissions[$role]) ? self::$permissions[$role] : [];
    }
}
?>
