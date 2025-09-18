require_once __DIR__ . '/../Helper/DataBaseHelper.php';
<?php
use PHPUnit\Framework\TestCase;

/**
 * Tests unitaires pour la classe User
 */
class UserTest extends TestCase {
    private $db;
    private $user;

    protected function setUp(): void {
        $this->db = DatabaseTestHelper::getTestConnection();
        $this->user = new User($this->db);
        DatabaseTestHelper::cleanDatabase();
    }

    protected function tearDown(): void {
        DatabaseTestHelper::cleanDatabase();
    }

    public function testCreateUser() {
        $this->user->nom = "Jean Dupont";
        $this->user->email = "jean.dupont@example.com";
        $this->user->mot_de_passe = "password123";
        $this->user->role = "visiteur";

        $result = $this->user->create();

        $this->assertTrue($result);
        $this->assertNotNull($this->user->id);
        $this->assertIsNumeric($this->user->id);
    }

    public function testEmailExists() {
        // Créer un utilisateur
        DatabaseTestHelper::createTestUser(['email' => 'existing@example.com']);

        // Tester avec un email existant
        $exists = $this->user->emailExists('existing@example.com');
        $this->assertTrue($exists);
        $this->assertEquals('existing@example.com', $this->user->email);

        // Tester avec un email inexistant
        $notExists = $this->user->emailExists('nonexistent@example.com');
        $this->assertFalse($notExists);
    }

    public function testLogin() {
        // Créer un utilisateur avec un mot de passe connu
        $password = 'testpassword123';
        DatabaseTestHelper::createTestUser([
            'email' => 'login@example.com',
            'mot_de_passe' => password_hash($password, PASSWORD_BCRYPT)
        ]);

        // Test de connexion réussie
        $loginSuccess = $this->user->login('login@example.com', $password);
        $this->assertTrue($loginSuccess);

        // Test de connexion échouée (mauvais mot de passe)
        $loginFail = $this->user->login('login@example.com', 'wrongpassword');
        $this->assertFalse($loginFail);

        // Test de connexion échouée (email inexistant)
        $loginFailEmail = $this->user->login('nonexistent@example.com', $password);
        $this->assertFalse($loginFailEmail);
    }

    public function testReadOne() {
        $userId = DatabaseTestHelper::createTestUser([
            'nom' => 'Test ReadOne',
            'email' => 'readone@example.com'
        ]);

        $result = $this->user->readOne($userId);

        $this->assertTrue($result);
        $this->assertEquals($userId, $this->user->id);
        $this->assertEquals('Test ReadOne', $this->user->nom);
        $this->assertEquals('readone@example.com', $this->user->email);
    }

    public function testUpdate() {
        $userId = DatabaseTestHelper::createTestUser();
        
        $this->user->readOne($userId);
        $this->user->nom = "Nom Modifié";
        $this->user->email = "modified@example.com";

        $result = $this->user->update();

        $this->assertTrue($result);

        // Vérifier que les modifications ont été sauvegardées
        $updatedUser = new User($this->db);
        $updatedUser->readOne($userId);
        $this->assertEquals("Nom Modifié", $updatedUser->nom);
        $this->assertEquals("modified@example.com", $updatedUser->email);
    }

    public function testDelete() {
        $userId = DatabaseTestHelper::createTestUser();
        
        $this->user->id = $userId;
        $result = $this->user->delete();

        $this->assertTrue($result);

        // Vérifier que l'utilisateur n'existe plus
        $deletedUser = new User($this->db);
        $exists = $deletedUser->readOne($userId);
        $this->assertFalse($exists);
    }

    public function testValidate() {
        // Test avec des données valides
        $this->user->nom = "Jean Dupont";
        $this->user->email = "jean@example.com";
        $this->user->mot_de_passe = "password123";
        $this->user->role = "visiteur";

        $errors = $this->user->validate();
        $this->assertEmpty($errors);

        // Test avec des données invalides
        $this->user->nom = "J"; // Trop court
        $this->user->email = "invalid-email"; // Email invalide
        $this->user->mot_de_passe = "123"; // Trop court
        $this->user->role = "invalid"; // Rôle invalide

        $errors = $this->user->validate();
        $this->assertNotEmpty($errors);
        $this->assertCount(4, $errors);
    }
}
?>
