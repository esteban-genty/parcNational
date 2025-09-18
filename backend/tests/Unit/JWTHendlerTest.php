<?php
require_once __DIR__ . '/../Helper/DatabaseTestHelper.php';

use PHPUnit\Framework\TestCase;

/**
 * Tests unitaires pour la classe JWTHandler
 */
class JWTHandlerTest extends TestCase {

    public function testGenerateToken() {
        $payload = [
            'user_id' => 1,
            'email' => 'test@example.com',
            'role' => 'visiteur'
        ];

        $token = JWTHandler::generateToken($payload);

        $this->assertIsString($token);
        $this->assertNotEmpty($token);
        
        // Vérifier que le token a 3 parties séparées par des points
        $parts = explode('.', $token);
        $this->assertCount(3, $parts);
    }

    public function testVerifyValidToken() {
        $payload = [
            'user_id' => 1,
            'email' => 'test@example.com',
            'role' => 'admin'
        ];

        $token = JWTHandler::generateToken($payload);
        $decodedPayload = JWTHandler::verifyToken($token);

        $this->assertIsArray($decodedPayload);
        $this->assertEquals(1, $decodedPayload['user_id']);
        $this->assertEquals('test@example.com', $decodedPayload['email']);
        $this->assertEquals('admin', $decodedPayload['role']);
        $this->assertArrayHasKey('iat', $decodedPayload);
        $this->assertArrayHasKey('exp', $decodedPayload);
        $this->assertArrayHasKey('iss', $decodedPayload);
    }

    public function testVerifyInvalidToken() {
        $invalidToken = "invalid.token.here";
        $result = JWTHandler::verifyToken($invalidToken);

        $this->assertFalse($result);
    }

    public function testVerifyTamperedToken() {
        $payload = ['user_id' => 1, 'role' => 'visiteur'];
        $token = JWTHandler::generateToken($payload);
        
        // Modifier le token (simulation de tampering)
        $tamperedToken = $token . 'tampered';
        $result = JWTHandler::verifyToken($tamperedToken);

        $this->assertFalse($result);
    }

    public function testCreateUserPayload() {
        $user = new stdClass();
        $user->id = 123;
        $user->email = 'user@example.com';
        $user->nom = 'Test User';
        $user->role = 'admin';

        $payload = JWTHandler::createUserPayload($user);

        $this->assertIsArray($payload);
        $this->assertEquals(123, $payload['user_id']);
        $this->assertEquals('user@example.com', $payload['email']);
        $this->assertEquals('Test User', $payload['nom']);
        $this->assertEquals('admin', $payload['role']);
    }

    public function testGenerateRefreshToken() {
        $userId = 42;
        $refreshToken = JWTHandler::generateRefreshToken($userId);

        $this->assertIsString($refreshToken);
        $this->assertNotEmpty($refreshToken);

        // Vérifier que c'est un token valide
        $payload = JWTHandler::verifyRefreshToken($refreshToken);
        $this->assertIsArray($payload);
        $this->assertEquals($userId, $payload['user_id']);
        $this->assertEquals('refresh', $payload['type']);
    }

    public function testVerifyRefreshToken() {
        $userId = 99;
        $refreshToken = JWTHandler::generateRefreshToken($userId);
        $payload = JWTHandler::verifyRefreshToken($refreshToken);

        $this->assertIsArray($payload);
        $this->assertEquals($userId, $payload['user_id']);
        $this->assertEquals('refresh', $payload['type']);

        // Test avec un token d'accès normal (ne devrait pas fonctionner)
        $accessToken = JWTHandler::generateToken(['user_id' => $userId]);
        $result = JWTHandler::verifyRefreshToken($accessToken);
        $this->assertFalse($result);
    }
}
?>
