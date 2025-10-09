<?php
use PHPUnit\Framework\TestCase;

class SentierTest extends TestCase {
    private $pdo;
    private $sentier;

    protected function setUp(): void {
        $database = new Database();
        $this->pdo = $database->getConnection();
        $this->sentier = new Sentier($this->pdo);
        $this->pdo->exec('DELETE FROM sentier');
    }

    public function testCreateSentier() {
        $this->sentier->nom = 'Sentier du Lac';
        $this->sentier->difficulte = 'facile';
        $this->sentier->description = 'Balade autour du lac.';
        $result = $this->sentier->create();
        $this->assertTrue($result['success']);
        $this->assertNotEmpty($result['id']);
    }
    // Tests unitaires Sentier supprimés (non essentiels)
    public function testReadSentier() {
        $this->sentier->nom = 'Sentier du Pic';
        $this->sentier->difficulte = 'difficile';
        $this->sentier->description = 'Montée raide.';
        $result = $this->sentier->create();
        $this->assertTrue($result['success']);
        $id = $result['id'];
        $found = $this->sentier->read($id);
        $this->assertTrue($found);
        $this->assertEquals('Sentier du Pic', $this->sentier->nom);
    }
}
