<?php
use PHPUnit\Framework\TestCase;

/**
 * Tests d'intégration pour le flux d'authentification complet
 */
class AuthenticationFlowTest extends TestCase {
    private $db;

    protected function setUp(): void {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->db->exec('DELETE FROM visiteur');
        $this->db->exec('DELETE FROM utilisateur');
    }

    protected function tearDown(): void {
        $this->db->exec('DELETE FROM visiteur');
        $this->db->exec('DELETE FROM utilisateur');
    }

    public function testCompleteRegistrationAndLoginFlow() {
        $user = new User($this->db);
        $user->nom = 'Test Integration';
        $user->email = 'integration@example.com';
        $user->mot_de_passe = 'Password123';
        $user->role = 'visiteur';

        $result = $user->create();
        $this->assertTrue($result['success']);
        $userId = $result['id'];

        $visiteur = new Visiteur($this->db);
        $visiteur->utilisateur_id = $userId;
        $visiteur->abonnement = 'premium';
        $visiteur->carte_membre = 'CARD123';
        $visiteurCreated = $visiteur->create();
        $this->assertTrue($visiteurCreated);

        $loginUser = new User($this->db);
        $this->assertTrue($loginUser->login('integration@example.com', 'Password123'));

        $payload = JWTHandler::createUserPayload($loginUser);
        $token = JWTHandler::generateToken($payload);
        $this->assertNotEmpty($token);

        $decodedPayload = JWTHandler::verifyToken($token);
        $this->assertIsArray($decodedPayload);
        $this->assertEquals($userId, $decodedPayload['user_id']);
        $this->assertEquals('integration@example.com', $decodedPayload['email']);

        $refreshToken = JWTHandler::generateRefreshToken($userId);
        $refreshPayload = JWTHandler::verifyRefreshToken($refreshToken);
        $this->assertIsArray($refreshPayload);
        $this->assertEquals($userId, $refreshPayload['user_id']);
    }
}
