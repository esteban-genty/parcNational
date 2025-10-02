<?php
use PHPUnit\Framework\TestCase;

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
        $data = [
            'titre' => 'Alerte météo',
            'message' => 'Orage prévu demain.',
            'date_envoi' => '2025-09-24'
        ];
        $result = $this->notification->create($data);
        $this->assertIsArray($result);
    }

    public function testReadNotification() {
        $data = [
            'titre' => 'Alerte incendie',
            'message' => 'Feu maîtrisé.',
            'date_envoi' => '2025-09-25'
        ];
        $result = $this->notification->create($data);
        $id = $result['id'];
        $found = $this->notification->read($id);
        $this->assertTrue($found);
    }
}
