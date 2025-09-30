<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../models/Reservation.php';
require_once __DIR__ . '/../../config/database.php';

class ReservationTest extends TestCase {
    private $pdo;
    private $reservation;

    protected function setUp(): void {
        $database = new Database();
        $this->pdo = $database->getConnection();
        $this->reservation = new Reservation($this->pdo);
        $this->pdo->exec('DELETE FROM reservation');
    }

    public function testCreateReservation() {
        $this->reservation->visiteur_id = 1;
        $this->reservation->camping_id = 1;
        $this->reservation->date_debut = '2025-10-01';
        $this->reservation->date_fin = '2025-10-05';
        $result = $this->reservation->create();
        $this->assertTrue($result);
    }

    public function testReadReservation() {
        $this->reservation->visiteur_id = 1;
        $this->reservation->camping_id = 1;
        $this->reservation->date_debut = '2025-10-10';
        $this->reservation->date_fin = '2025-10-12';
        $this->reservation->create();
        $id = $this->pdo->lastInsertId();
        $found = $this->reservation->read($id);
        $this->assertTrue($found);
        $this->assertEquals(1, $this->reservation->visiteur_id);
    }
}
