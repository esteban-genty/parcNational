<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/database.php';
require_once __DIR__ . '/../src/auth.php';

class AuthTest extends TestCase
{
    private $auth;

    protected function setUp(): void
    {
        $db = new Database();
        $this->auth = new Auth($db);
    }

    public function testRegisterAndLogin()
    {
        $nom = "TestUser";
        $email = "testuser_phpunit@example.com";
        $mot_de_passe = "password123";
        $role = "visiteur";

        // Test inscription
        $result = $this->auth->register($nom, $email, $mot_de_passe, $role);
        $this->assertTrue($result);

        // Test connexion
        $login = $this->auth->login($email, $mot_de_passe);
        $this->assertTrue($login);
    }
}