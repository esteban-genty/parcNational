<?php
require_once __DIR__ . '/../Helper/DatabaseTestHelper.php';

use PHPUnit\Framework\TestCase;

/**
 * Tests d'intégration pour le flux d'authentification complet
 */
class AuthenticationFlowTest extends TestCase {
    private $db;

    protected function setUp(): void {
        $this->db = DatabaseTestHelper::getTestConnection();
        DatabaseTestHelper::cleanDatabase();
    }

    protected function tearDown(): void {
        DatabaseTestHelper::cleanDatabase();
    }

    public function testCompleteRegistrationAndLoginFlow() {
        // 1. Inscription d'un nouvel utilisateur
        $user = new User($this->db);
        $user->nom = "Test Integration";
        $user->email = "integration@example.com";
        $user->mot_de_passe = "Password123";
        $user->role = "visiteur";

        $this->assertTrue($user->create());
        $userId = $user->id;

        // 2. Création du profil visiteur
        $visiteur = new Visiteur($this->db);
        $visiteur->utilisateur_id = $userId;
        $visiteur->abonnement = "premium";
        $visiteur->carte_membre = "CARD123";

        $this->assertTrue($visiteur->create());

        // 3. Connexion avec les identifiants
        $loginUser = new User($this->db);
        $this->assertTrue($loginUser->login("integration@example.com", "Password123"));

        // 4. Génération du token JWT
        $payload = JWTHandler::createUserPayload($loginUser);
        $token = JWTHandler::generateToken($payload);

        $this->assertNotEmpty($token);

        // 5. Vérification du token
        $decodedPayload = JWTHandler::verifyToken($token);
        $this->assertIsArray($decodedPayload);
        $this->assertEquals($userId, $decodedPayload['user_id']);
        $this->assertEquals("integration@example.com", $decodedPayload['email']);

        // 6. Test du refresh token
        $refreshToken = JWTHandler::generateRefreshToken($userId);
        $refreshPayload = JWTHandler::verifyRefreshToken($refreshToken);
        $this->assertIsArray($refreshPayload);
        $this->assertEquals($userId, $refreshPayload['user_id']);
    }

    public function testUserUpdateFlow() {
        // Créer un utilisateur
        $userId = DatabaseTestHelper::createTestUser([
            'nom' => 'Original Name',
            'email' => 'original@example.com'
        ]);

        // Lire l'utilisateur
        $user = new User($this->db);
        $this->assertTrue($user->readOne($userId));

        // Modifier les données
        $user->nom = "Updated Name";
        $user->email = "updated@example.com";

        // Sauvegarder
        $this->assertTrue($user->update());

        // Vérifier les modifications
        $updatedUser = new User($this->db);
        $this->assertTrue($updatedUser->readOne($userId));
        $this->assertEquals("Updated Name", $updatedUser->nom);
        $this->assertEquals("updated@example.com", $updatedUser->email);
    }

    public function testVisiteurProfileFlow() {
        // Créer un utilisateur visiteur
        $userId = DatabaseTestHelper::createTestUser(['role' => 'visiteur']);

        // Créer le profil visiteur
        $visiteur = new Visiteur($this->db);
        $visiteur->utilisateur_id = $userId;
        $visiteur->abonnement = "standard";
        $visiteur->carte_membre = "VISITOR001";

        $this->assertTrue($visiteur->create());

        // Lire le profil visiteur
        $readVisiteur = new Visiteur($this->db);
        $this->assertTrue($readVisiteur->readByUserId($userId));
        $this->assertEquals("standard", $readVisiteur->abonnement);
        $this->assertEquals("VISITOR001", $readVisiteur->carte_membre);

        // Mettre à jour le profil
        $readVisiteur->abonnement = "premium";
        $this->assertTrue($readVisiteur->update());

        // Vérifier la mise à jour
        $finalVisiteur = new Visiteur($this->db);
        $this->assertTrue($finalVisiteur->readByUserId($userId));
        $this->assertEquals("premium", $finalVisiteur->abonnement);
    }
}
?>
