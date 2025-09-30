<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/AuthModel.php';

class AuthTest extends TestCase
{
    private $auth;

    protected function setUp(): void
    {
        $this->auth = new AuthModel();
    }

    public function testRegisterAndLogin()
    {
        $nom = "TestUser";
        $email = "testuser_phpunit@example.com";
        $mot_de_passe = "password123";
        $role = "visiteur";

        // Test inscription
        $user = $this->auth->register($nom, $email, $mot_de_passe, $role);
        $this->assertIsArray($user, 'L\'inscription doit retourner un tableau utilisateur');
        $this->assertEquals($email, $user['email']);

        // Test connexion
        $loginUser = $this->auth->login($email, $mot_de_passe);
        $this->assertIsArray($loginUser, 'La connexion doit retourner un tableau utilisateur');
        $this->assertEquals($email, $loginUser['email']);
    }
}