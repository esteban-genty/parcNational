<?php
/**
 * Modèle Camping - Pattern SOLID
 * Single Responsibility: Gère uniquement les données camping
 */
require_once __DIR__ . '/Database.php';

class Camping extends Database {
    private $db;

    public function __construct() {
        $this->db = $this->connect();
    }

    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM camping");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM camping WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(string $nom, string $localisation, int $capacite, ?string $equipements = null): ?array {
        if ($capacite <= 0) return null;
        
        // Vérifier si la colonne equipements existe
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO camping (nom, localisation, capacite, equipements) VALUES (?, ?, ?, ?)"
            );
            $stmt->execute([$nom, $localisation, $capacite, $equipements]);
        } catch (PDOException $e) {
            // Si la colonne n'existe pas, utiliser la version simple
            $stmt = $this->db->prepare(
                "INSERT INTO camping (nom, localisation, capacite) VALUES (?, ?, ?)"
            );
            $stmt->execute([$nom, $localisation, $capacite]);
        }
        
        return $this->findById($this->db->lastInsertId());
    }

    public function update(int $id, ?string $nom, ?string $localisation, ?int $capacite, ?string $equipements): ?array {
        $camping = $this->findById($id);
        if (!$camping) return null;
        
        // Vérifier si la colonne equipements existe
        try {
            $stmt = $this->db->prepare(
                "UPDATE camping SET nom = ?, localisation = ?, capacite = ?, equipements = ? WHERE id = ?"
            );
            $stmt->execute([
                $nom ?? $camping['nom'],
                $localisation ?? $camping['localisation'],
                $capacite ?? $camping['capacite'],
                $equipements ?? $camping['equipements'] ?? null,
                $id
            ]);
        } catch (PDOException $e) {
            // Si la colonne n'existe pas, utiliser la version simple
            $stmt = $this->db->prepare(
                "UPDATE camping SET nom = ?, localisation = ?, capacite = ? WHERE id = ?"
            );
            $stmt->execute([
                $nom ?? $camping['nom'],
                $localisation ?? $camping['localisation'],
                $capacite ?? $camping['capacite'],
                $id
            ]);
        }
        
        return $this->findById($id);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM camping WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

