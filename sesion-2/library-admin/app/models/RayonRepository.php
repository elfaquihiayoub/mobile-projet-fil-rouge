<?php

class RayonRepository
{
    public function __construct(private PDO $db)
    {
    }

    public function all(): array
    {
        $rows = $this->db->query('SELECT * FROM rayon ORDER BY nom_rayon')->fetchAll();
        return array_map(static fn (array $row): array => Rayon::fromArray($row)->toArray(), $rows);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM rayon WHERE id_rayon = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ? Rayon::fromArray($row)->toArray() : null;
    }

    public function create(Rayon $rayon): bool
    {
        $stmt = $this->db->prepare('INSERT INTO rayon (nom_rayon, emplacement) VALUES (?, ?)');
        return $stmt->execute([$rayon->getNom(), $rayon->getEmplacement()]);
    }

    public function update(Rayon $rayon): bool
    {
        if ($rayon->getId() === null) {
            return false;
        }

        $stmt = $this->db->prepare('UPDATE rayon SET nom_rayon = ?, emplacement = ? WHERE id_rayon = ?');
        return $stmt->execute([$rayon->getNom(), $rayon->getEmplacement(), $rayon->getId()]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM rayon WHERE id_rayon = ?');
        return $stmt->execute([$id]);
    }
}
