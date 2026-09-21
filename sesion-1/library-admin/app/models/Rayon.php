<?php

class Rayon
{
    public function __construct(private PDO $db) {}

    public function all(): array
    {
        return $this->db->query('SELECT * FROM rayon ORDER BY nom_rayon')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM rayon WHERE id_rayon = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO rayon (nom_rayon, emplacement) VALUES (?, ?)');
        return $stmt->execute([$data['nom_rayon'], $data['emplacement']]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE rayon SET nom_rayon = ?, emplacement = ? WHERE id_rayon = ?');
        return $stmt->execute([$data['nom_rayon'], $data['emplacement'], $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM rayon WHERE id_rayon = ?');
        return $stmt->execute([$id]);
    }
}
