<?php
use PHPUnit\Framework\TestCase;

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
        $data = [
            'visiteur_id' => 1,
            'camping_id' => 1,
            'date_debut' => '2025-10-01',
            'date_fin' => '2025-10-05'
        ];
        $result = $this->reservation->create($data);
        $this->assertIsArray($result);
    }

    public function testReadReservation() {
        $data = [
            'visiteur_id' => 1,
            'camping_id' => 1,
            'date_debut' => '2025-10-10',
            'date_fin' => '2025-10-12'
        ];
        $result = $this->reservation->create($data);
        $id = $result['id'];
        $found = $this->reservation->read($id);
        $this->assertTrue($found);
    }
}
