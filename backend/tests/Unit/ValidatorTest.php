<?php
use PHPUnit\Framework\TestCase;

/**
 * Tests unitaires pour la classe Validator
 */
class ValidatorTest extends TestCase {

    public function testValidateEmail() {
        // Emails valides
        $this->assertTrue(Validator::validateEmail('test@example.com'));
        $this->assertTrue(Validator::validateEmail('user.name@domain.co.uk'));
        $this->assertTrue(Validator::validateEmail('test+tag@example.org'));

        // Emails invalides
        $this->assertFalse(Validator::validateEmail('invalid-email'));
        $this->assertFalse(Validator::validateEmail('test@'));
        $this->assertFalse(Validator::validateEmail('@example.com'));
        $this->assertFalse(Validator::validateEmail(''));
    }

    public function testValidatePassword() {
        // Mot de passe valide
        $errors = Validator::validatePassword('Password123');
        $this->assertEmpty($errors);

        // Mot de passe trop court
        $errors = Validator::validatePassword('Pw1');
        $this->assertContains("Le mot de passe doit contenir au moins 6 caractères", $errors);

        // Mot de passe sans majuscule
        $errors = Validator::validatePassword('password123');
        $this->assertContains("Le mot de passe doit contenir au moins une majuscule", $errors);

        // Mot de passe sans minuscule
        $errors = Validator::validatePassword('PASSWORD123');
        $this->assertContains("Le mot de passe doit contenir au moins une minuscule", $errors);

        // Mot de passe sans chiffre
        $errors = Validator::validatePassword('Password');
        $this->assertContains("Le mot de passe doit contenir au moins un chiffre", $errors);
    }

    public function testSanitizeString() {
        // Test de nettoyage normal
        $input = "  <script>alert('test')</script>  ";
        $result = Validator::sanitizeString($input);
        $this->assertEquals("alert('test')", $result);

        // Test de longueur maximale
        $longString = str_repeat('a', 300);
        $result = Validator::sanitizeString($longString, 100);
        $this->assertFalse($result);

        // Test avec chaîne valide
        $validString = "Nom Valide";
        $result = Validator::sanitizeString($validString);
        $this->assertEquals("Nom Valide", $result);
    }

    public function testValidateRole() {
        // Rôles valides
        $this->assertTrue(Validator::validateRole('admin'));
        $this->assertTrue(Validator::validateRole('visiteur'));

        // Rôles invalides
        $this->assertFalse(Validator::validateRole('superadmin'));
        $this->assertFalse(Validator::validateRole('user'));
        $this->assertFalse(Validator::validateRole(''));
        $this->assertFalse(Validator::validateRole('ADMIN')); // Sensible à la casse
    }

    public function testValidateRegistration() {
        // Données valides
        $validData = [
            'nom' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'mot_de_passe' => 'Password123',
            'role' => 'visiteur'
        ];
        $errors = Validator::validateRegistration($validData);
        $this->assertEmpty($errors);

        // Données invalides
        $invalidData = [
            'nom' => 'J',
            'email' => 'invalid-email',
            'mot_de_passe' => '123',
            'role' => 'invalid'
        ];
        $errors = Validator::validateRegistration($invalidData);
        $this->assertNotEmpty($errors);
        $this->assertGreaterThan(3, count($errors));
    }
}
?>
