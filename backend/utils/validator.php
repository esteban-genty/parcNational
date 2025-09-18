<?php
/**
 * Classe utilitaire pour la validation des données
 */
class Validator {
    
    /**
     * Valide un email
     * @param string $email
     * @return bool
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Valide un mot de passe
     * @param string $password
     * @param int $minLength
     * @return array
     */
    public static function validatePassword($password, $minLength = 6) {
        $errors = [];
        
        if(strlen($password) < $minLength) {
            $errors[] = "Le mot de passe doit contenir au moins {$minLength} caractères";
        }
        
        if(!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Le mot de passe doit contenir au moins une majuscule";
        }
        
        if(!preg_match('/[a-z]/', $password)) {
            $errors[] = "Le mot de passe doit contenir au moins une minuscule";
        }
        
        if(!preg_match('/[0-9]/', $password)) {
            $errors[] = "Le mot de passe doit contenir au moins un chiffre";
        }
        
        return $errors;
    }

    /**
     * Nettoie et valide une chaîne de caractères
     * @param string $input
     * @param int $maxLength
     * @return string|false
     */
    public static function sanitizeString($input, $maxLength = 255) {
        $cleaned = trim(htmlspecialchars(strip_tags($input)));
        
        if(strlen($cleaned) > $maxLength) {
            return false;
        }
        
        return $cleaned;
    }

    /**
     * Valide un rôle utilisateur
     * @param string $role
     * @return bool
     */
    public static function validateRole($role) {
        $validRoles = ['admin', 'visiteur'];
        return in_array($role, $validRoles);
    }

    /**
     * Valide les données d'inscription
     * @param array $data
     * @return array
     */
    public static function validateRegistration($data) {
        $errors = [];

        // Validation du nom
        if(empty($data['nom']) || strlen(trim($data['nom'])) < 2) {
            $errors[] = "Le nom doit contenir au moins 2 caractères";
        }

        // Validation de l'email
        if(empty($data['email']) || !self::validateEmail($data['email'])) {
            $errors[] = "Email invalide";
        }

        // Validation du mot de passe
        if(empty($data['mot_de_passe'])) {
            $errors[] = "Le mot de passe est requis";
        } else {
            $passwordErrors = self::validatePassword($data['mot_de_passe']);
            $errors = array_merge($errors, $passwordErrors);
        }

        // Validation du rôle
        if(isset($data['role']) && !self::validateRole($data['role'])) {
            $errors[] = "Rôle invalide";
        }

        return $errors;
    }
}
?>
