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
        $data = [
            'nom' => 'Camping Test',
            'localisation' => 'Calanques',
            'capacite' => 50
        ];
        $result = $this->camping->create($data);
        $this->assertIsArray($result);
    }

    public function testReadCamping() {
        $data = [
            'nom' => 'Camping Read',
            'localisation' => 'Calanques',
            'capacite' => 30
        ];
        $this->camping->create($data);
        $id = $this->pdo->lastInsertId();
        $found = $this->camping->read($id);
        $this->assertTrue($found);
    }
}
