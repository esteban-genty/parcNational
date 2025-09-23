<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../models/RessourceNaturelle.php';
require_once __DIR__ . '/../../config/database.php';

class RessourceNaturelleTest extends TestCase {
    private $pdo;
    private $ressource;

    protected function setUp(): void {
        $database = new Database();
        $this->pdo = $database->getConnection();
        $this->ressource = new RessourceNaturelle($this->pdo);
        $this->pdo->exec('DELETE FROM ressource_naturelle');
    }

    public function testCreateRessource() {
        $this->ressource->type = 'Eau';
        $this->ressource->nom = 'Source du Vallon';
        $this->ressource->etat = 'Propre';
        $result = $this->ressource->create();
        $this->assertTrue($result, 'La création doit réussir');
    }

    public function testCreateRessourceFail() {
        $this->ressource->type = '';
        $this->ressource->nom = '';
        $this->ressource->etat = '';
        $result = $this->ressource->create();
        $this->assertFalse($result, 'La création doit échouer si le type est vide');
    }

    public function testReadRessource() {
        $this->ressource->type = 'Flore';
        $this->ressource->nom = 'Chêne vert';
        $this->ressource->etat = 'Sain';
        $this->ressource->create();
        $id = $this->pdo->lastInsertId();
        $found = $this->ressource->read($id);
        $this->assertTrue($found, 'La lecture doit réussir');
        $this->assertEquals('Chêne vert', $this->ressource->nom, 'Le nom doit correspondre');
    }

    public function testReadRessourceFail() {
        $found = $this->ressource->read(9999);
        $this->assertFalse($found, 'La lecture doit échouer si l’id n’existe pas');
    }
}
