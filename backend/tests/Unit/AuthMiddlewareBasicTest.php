<?php
/**
 * Tests SIMPLES pour AuthMiddleware
 * Vérifie juste les fonctions principales
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../utils/AuthMiddelware.php';
require_once __DIR__ . '/../../utils/JWTHandler.php';

class AuthMiddlewareBasicTest extends TestCase {
    
    protected function setUp(): void {
        parent::setUp();
        if (ob_get_level() === 0) ob_start();
        unset($_SERVER['HTTP_AUTHORIZATION']);
    }
    
    protected function tearDown(): void {
        while (ob_get_level() > 0) ob_end_clean();
        parent::tearDown();
    }
    
    /**
     * TEST 1 : Un token JWT valide doit être accepté
     */
    public function testAuthenticateWithValidToken() {
        // Créer un token valide
        $payload = ['user_id' => 1, 'role' => 'admin', 'iat' => time(), 'exp' => time() + 3600];
        $token = JWTHandler::generateToken($payload);
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;
        
        // Vérifier qu'il est accepté
        $result = AuthMiddleware::authenticate();
        
        $this->assertIsArray($result);
        $this->assertEquals('admin', $result['role']);
    }
    
    /**
     * TEST 2 : Un faux token doit être rejeté
     */
    public function testAuthenticateWithInvalidToken() {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer fake_token';
        
        $result = AuthMiddleware::authenticate();
        
        $this->assertFalse($result);
        $this->assertEquals(401, http_response_code());
    }
    
    /**
     * TEST 3 : Sans token = rejeté
     */
    public function testAuthenticateWithoutToken() {
        $result = AuthMiddleware::authenticate();
        
        $this->assertFalse($result);
        $this->assertEquals(401, http_response_code());
    }
    
    /**
     * TEST 4 : requireAdmin() accepte un admin
     */
    public function testRequireAdminAcceptsAdmin() {
        $payload = ['user_id' => 1, 'role' => 'admin', 'iat' => time(), 'exp' => time() + 3600];
        $token = JWTHandler::generateToken($payload);
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;
        
        $result = AuthMiddleware::requireAdmin();
        
        $this->assertIsArray($result);
        $this->assertEquals('admin', $result['role']);
    }
    
    /**
     * TEST 5 : requireAdmin() rejette un visiteur
     */
    public function testRequireAdminRejectsVisiteur() {
        $payload = ['user_id' => 2, 'role' => 'visiteur', 'iat' => time(), 'exp' => time() + 3600];
        $token = JWTHandler::generateToken($payload);
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;
        
        $result = AuthMiddleware::requireAdmin();
        
        $this->assertFalse($result);
        $this->assertEquals(403, http_response_code());
    }
}
