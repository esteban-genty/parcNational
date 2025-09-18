<?php
/**
 * Helper pour les tests de base de données
 */
class DatabaseTestHelper {
    private static $pdo = null;

    /**
     * Obtient une connexion de test à la base de données
     */
    public static function getTestConnection() {
        if (self::$pdo === null) {
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $dbname = $_ENV['DB_NAME'] ?? 'parc_national_test';
            $username = $_ENV['DB_USER'] ?? 'root';
            $password = $_ENV['DB_PASS'] ?? '';

            try {
                self::$pdo = new PDO(
                    "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                throw new Exception("Connexion test DB échouée: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }

    /**
     * Nettoie les tables pour les tests
     */
    public static function cleanDatabase() {
        $pdo = self::getTestConnection();
        
        $tables = ['reservation', 'visiteur', 'utilisateur', 'camping', 'sentier', 'ressource_naturelle', 'notification', 'carte_membre'];
        
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        
        foreach ($tables as $table) {
            $pdo->exec("TRUNCATE TABLE {$table}");
        }
        
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Crée un utilisateur de test
     */
    public static function createTestUser($data = []) {
        $pdo = self::getTestConnection();
        
        $defaultData = [
            'nom' => 'Test User',
            'email' => 'test@example.com',
            'mot_de_passe' => password_hash('password123', PASSWORD_BCRYPT),
            'role' => 'visiteur'
        ];
        
        $userData = array_merge($defaultData, $data);
        
        $stmt = $pdo->prepare("INSERT INTO utilisateur (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userData['nom'], $userData['email'], $userData['mot_de_passe'], $userData['role']]);
        
        return $pdo->lastInsertId();
    }
}
?>
