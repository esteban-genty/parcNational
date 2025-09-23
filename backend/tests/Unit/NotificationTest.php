<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../models/Notification.php';
require_once __DIR__ . '/../../config/database.php';

class NotificationTest extends TestCase {
    private $pdo;
    private $notification;

    protected function setUp(): void {
        $database = new Database();
        $this->pdo = $database->getConnection();
        $this->notification = new Notification($this->pdo);
        $this->pdo->exec('DELETE FROM notification');
    }

    public function testCreateNotification() {
        $this->notification->titre = 'Alerte météo';
        $this->notification->message = 'Orage prévu demain.';
        $this->notification->date_envoi = '2025-09-24';
        $result = $this->notification->create();
        $this->assertTrue($result, 'La création doit réussir');
    }

    public function testCreateNotificationFail() {
        $this->notification->titre = '';
        $this->notification->message = '';
        $this->notification->date_envoi = '';
        $result = $this->notification->create();
        $this->assertFalse($result, 'La création doit échouer si les données sont invalides');
    }

    public function testReadNotification() {
        $this->notification->titre = 'Alerte incendie';
        $this->notification->message = 'Feu maîtrisé.';
        $this->notification->date_envoi = '2025-09-25';
        $this->notification->create();
        $id = $this->pdo->lastInsertId();
        $found = $this->notification->read($id);
        $this->assertTrue($found, 'La lecture doit réussir');
        $this->assertEquals('Alerte incendie', $this->notification->titre, 'Le titre doit correspondre');
    }

    public function testReadNotificationFail() {
        $found = $this->notification->read(9999);
        $this->assertFalse($found, 'La lecture doit échouer si l’id n’existe pas');
    }
}
