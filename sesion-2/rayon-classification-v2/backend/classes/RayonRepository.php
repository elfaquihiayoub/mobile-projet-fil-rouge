<?php

require_once __DIR__ . '/Rayon.php';

class RayonRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM rayon ORDER BY nom_rayon');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $rayons = [];
        foreach ($rows as $row) {
            $rayons[] = new Rayon(
                $row['nom_rayon'],
                $row['emplacement'],
                (int) $row['id_rayon']
            );
        }

        return $rayons;
    }

    public function create(Rayon $rayon): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO rayon (nom_rayon, emplacement) VALUES (?, ?)');
        return $stmt->execute([
            $rayon->getNom(),
            $rayon->getEmplacement(),
        ]);
    }

    public function update(Rayon $rayon): bool
    {
        $stmt = $this->pdo->prepare('UPDATE rayon SET nom_rayon = ?, emplacement = ? WHERE id_rayon = ?');
        return $stmt->execute([
            $rayon->getNom(),
            $rayon->getEmplacement(),
            $rayon->getId(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM rayon WHERE id_rayon = ?');
        return $stmt->execute([$id]);
    }
}
