<?php
use PHPUnit\Framework\TestCase;

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
        $data = [
            'type' => 'Eau',
            'nom' => 'Source du Vallon',
            'etat' => 'Propre'
        ];
        $result = $this->ressource->create($data);
        $this->assertIsArray($result);
    }

    public function testReadRessource() {
        $data = [
            'type' => 'Flore',
            'nom' => 'Chêne vert',
            'etat' => 'Sain'
        ];
        $result = $this->ressource->create($data);
        $id = $result['id'];
        $found = $this->ressource->read($id);
        $this->assertTrue($found);
    }
}
