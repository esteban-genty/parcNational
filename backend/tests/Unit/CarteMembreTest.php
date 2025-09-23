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
        $this->carte->numero_carte = 'CARD001';
        $this->carte->type_carte = 'premium';
        $this->carte->date_expiration = '2026-01-01';
        $result = $this->carte->create();
        $this->assertTrue($result, 'La création doit réussir');
    }

    public function testCreateCarteFail() {
        $this->carte->numero_carte = '';
        $this->carte->type_carte = '';
        $this->carte->date_expiration = '';
        $result = $this->carte->create();
        $this->assertFalse($result, 'La création doit échouer si les données sont invalides');
    }

    public function testReadCarte() {
        $this->carte->numero_carte = 'CARD002';
        $this->carte->type_carte = 'vip';
        $this->carte->date_expiration = '2027-01-01';
        $this->carte->create();
        $id = $this->pdo->lastInsertId();
        $found = $this->carte->read($id);
        $this->assertTrue($found, 'La lecture doit réussir');
        $this->assertEquals('CARD002', $this->carte->numero_carte, 'Le numéro doit correspondre');
    }

    public function testReadCarteFail() {
        $found = $this->carte->read(9999);
        $this->assertFalse($found, 'La lecture doit échouer si l’id n’existe pas');
    }
}
