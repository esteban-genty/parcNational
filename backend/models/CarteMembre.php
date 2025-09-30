class CarteMembre {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findAll() {
        $sql = "SELECT * FROM carte_membre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $sql = "SELECT * FROM carte_membre WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO carte_membre (numero_carte, type_carte, date_expiration) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['numero_carte'] ?? null,
            $data['type_carte'] ?? null,
            $data['date_expiration'] ?? null
        ]);
        $id = $this->db->lastInsertId();
        return $this->findById($id);
    }

    public function update($data) {
        if (empty($data['id'])) return false;
        $sql = "UPDATE carte_membre SET numero_carte = ?, type_carte = ?, date_expiration = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['numero_carte'] ?? null,
            $data['type_carte'] ?? null,
            $data['date_expiration'] ?? null,
            $data['id']
        ]);
        return $this->findById($data['id']);
    }

    public function delete($id) {
        $row = $this->findById($id);
        $sql = "DELETE FROM carte_membre WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $row;
    }
}
    // ...existing code...
}
