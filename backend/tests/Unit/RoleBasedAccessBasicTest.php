<?php
/**
 * Tests SIMPLES pour RoleBasedAccess
 * Vérifie les permissions de base
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../utils/RoleBasedAcces.php';
require_once __DIR__ . '/../../utils/AuthMiddelware.php';
require_once __DIR__ . '/../../utils/JWTHandler.php';

class RoleBasedAccessBasicTest extends TestCase {
    
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
     * TEST 1 : Admin peut créer des utilisateurs
     */
    public function testAdminCanCreateUsers() {
        $payload = ['user_id' => 1, 'role' => 'admin', 'iat' => time(), 'exp' => time() + 3600];
        $token = JWTHandler::generateToken($payload);
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;
        
        $result = RoleBasedAccess::hasPermission('users.create');
        
        $this->assertTrue($result);
    }
    
    /**
     * TEST 2 : Visiteur NE PEUT PAS créer des utilisateurs
     */
    public function testVisiteurCannotCreateUsers() {
        $payload = ['user_id' => 2, 'role' => 'visiteur', 'iat' => time(), 'exp' => time() + 3600];
        $token = JWTHandler::generateToken($payload);
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;
        
        $result = RoleBasedAccess::hasPermission('users.create');
        
        $this->assertFalse($result);
    }
    
    /**
     * TEST 3 : Visiteur peut lire les campings
     */
    public function testVisiteurCanReadCampings() {
        $payload = ['user_id' => 2, 'role' => 'visiteur', 'iat' => time(), 'exp' => time() + 3600];
        $token = JWTHandler::generateToken($payload);
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;
        
        $result = RoleBasedAccess::hasPermission('campings.read');
        
        $this->assertTrue($result);
    }
    
    /**
     * TEST 4 : Visiteur peut créer des réservations
     */
    public function testVisiteurCanCreateReservations() {
        $payload = ['user_id' => 2, 'role' => 'visiteur', 'iat' => time(), 'exp' => time() + 3600];
        $token = JWTHandler::generateToken($payload);
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;
        
        $result = RoleBasedAccess::hasPermission('reservations.create');
        
        $this->assertTrue($result);
    }
    
    /**
     * TEST 5 : Admin peut tout faire
     */
    public function testAdminCanDeleteCampings() {
        $payload = ['user_id' => 1, 'role' => 'admin', 'iat' => time(), 'exp' => time() + 3600];
        $token = JWTHandler::generateToken($payload);
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;
        
        $result = RoleBasedAccess::hasPermission('campings.delete');
        
        $this->assertTrue($result);
    }
    
    /**
     * TEST 6 : Sans connexion = pas de permission
     */
    public function testNoAuthNoPermission() {
        $result = RoleBasedAccess::hasPermission('campings.read');
        
        $this->assertFalse($result);
    }
}
