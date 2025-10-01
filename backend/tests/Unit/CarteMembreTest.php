<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../models/CarteMembre.php';
require_once __DIR__ . '/../../config/database.php';

class CarteMembreTest extends TestCase {
    private $pdo;
    private $carte;

    protected function setUp(): void {
        $database = new Database();
        $this->pdo = $database->getConnection();
        $this->carte = new CarteMembre($this->pdo);
        $this->pdo->exec('DELETE FROM carte_membre');
    }

    public function testCreateCarte() {
        $data = [
            'numero_carte' => 'CARD001',
            'type_carte' => 'premium',
            'date_expiration' => '2026-01-01'
        ];
        $result = $this->carte->create($data);
        $this->assertIsArray($result);
    }

    public function testReadCarte() {
        $data = [
            'numero_carte' => 'CARD002',
            'type_carte' => 'vip',
            'date_expiration' => '2027-01-01'
        ];
        $this->carte->create($data);
        $id = $this->pdo->lastInsertId();
        $found = $this->carte->read($id);
        $this->assertTrue($found);
    }
}
