<?php
use PHPUnit\Framework\TestCase;

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
        $result = $this->camping->create('Camping Test', 'Calanques', 50);
        $this->assertIsArray($result);
        $this->assertEquals('Camping Test', $result['nom']);
        $this->assertEquals('Calanques', $result['localisation']);
        $this->assertEquals(50, $result['capacite']);
    }

    public function testReadCamping() {
        $result = $this->camping->create('Camping Read', 'Calanques', 30);
        $id = $result['id'];
        $found = $this->camping->findById($id);
        $this->assertIsArray($found);
        $this->assertEquals('Camping Read', $found['nom']);
    }
}
