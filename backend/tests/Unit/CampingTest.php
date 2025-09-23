<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../models/Camping.php';
require_once __DIR__ . '/../../config/database.php';

/**
 * Test unitaire pour le modèle Camping
 */
class CampingTest extends TestCase {
    private $db;
    private $camping;
    private $pdo;

    protected function setUp(): void {
        $database = new Database();
        $this->pdo = $database->getConnection();
        $this->camping = new Camping($this->pdo);
        // Nettoyage avant chaque test
        $this->pdo->exec('DELETE FROM camping');
    }

    public function testCreateCamping() {
        // Test création
        $this->camping->nom = 'Camping Test';
        $this->camping->localisation = 'Calanques';
        $this->camping->capacite = 50;
        $result = $this->camping->create();
        // Vérifie que la création fonctionne
        $this->assertTrue($result, 'La création doit réussir');
    }

    public function testCreateCampingFail() {
        // Test création avec données manquantes
        $this->camping->nom = '';
        $this->camping->localisation = '';
        $this->camping->capacite = 0;
        $result = $this->camping->create();
        // Vérifie que la création échoue
        $this->assertFalse($result, 'La création doit échouer si les données sont invalides');
    }

    public function testReadCamping() {
        // Crée un camping pour le test
    $this->camping->nom = 'Camping Read';
    $this->camping->localisation = 'Calanques';
    $this->camping->capacite = 30;
    $this->camping->create();
    $id = $this->pdo->lastInsertId();
    // Test lecture
    $found = $this->camping->read($id);
    $this->assertTrue($found, 'La lecture doit réussir');
    $this->assertEquals('Camping Read', $this->camping->nom, 'Le nom doit correspondre');
    }

    public function testReadCampingFail() {
        // Test lecture d'un camping inexistant
        $found = $this->camping->read(9999);
        $this->assertFalse($found, 'La lecture doit échouer si l’id n’existe pas');
    }
}
