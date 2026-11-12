<?php
declare(strict_types=1);

require_once __DIR__ . '/../classes/Rayon.php';

class RayonDAO
{
    private string $dataFile;

    public function __construct(?string $dataFile = null)
    {
        $this->dataFile = $dataFile ?? __DIR__ . '/../data/rayon.json';
    }

    /**
     * @return Rayon[]
     */
    public function getAll(): array
    {
        return array_map(
            fn (array $item): Rayon => $this->hydrate($item),
            $this->readData()
        );
    }

    public function findById(int $id): ?Rayon
    {
        foreach ($this->getAll() as $rayon) {
            if ($rayon->getId() === $id) {
                return $rayon;
            }
        }

        return null;
    }

    public function create(Rayon $rayon): int
    {
        $data = $this->readData();
        $id = 1;

        foreach ($data as $item) {
            $storedId = $this->hydrate($item)->getId();
            $id = max($id, ($storedId ?? 0) + 1);
        }

        $data[] = [
            'id' => $id,
            'nom' => $rayon->getNom(),
            'emplacement' => $rayon->getEmplacement(),
        ];
        $this->writeData($data);

        return $id;
    }

    public function update(int $id, Rayon $rayon): bool
    {
        $data = $this->readData();

        foreach ($data as &$item) {
            if ($this->hydrate($item)->getId() === $id) {
                $item = [
                    'id' => $id,
                    'nom' => $rayon->getNom(),
                    'emplacement' => $rayon->getEmplacement(),
                ];
                unset($item);
                $this->writeData($data);
                return true;
            }
        }
        unset($item);

        return false;
    }

    public function delete(int $id): bool
    {
        $data = $this->readData();

        foreach ($data as $index => $item) {
            if ($this->hydrate($item)->getId() === $id) {
                array_splice($data, $index, 1);
                $this->writeData($data);
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<int, array{id: int, nom: string, emplacement: ?string}>
     */
    private function readData(): array
    {
        $jsonData = file_get_contents($this->dataFile);
        if ($jsonData === false) {
            throw new RuntimeException('Impossible de lire les donnees des rayons.');
        }

        $data = json_decode($jsonData, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($data)) {
            throw new RuntimeException('Le fichier des rayons est invalide.');
        }

        foreach ($data as $item) {
            if (!is_array($item)) {
                throw new RuntimeException('Le fichier des rayons est invalide.');
            }
            $this->hydrate($item);
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $item
     */
    private function hydrate(array $item): Rayon
    {
        if (
            !isset($item['id'], $item['nom'])
            || !is_int($item['id'])
            || !is_string($item['nom'])
            || (isset($item['emplacement']) && !is_string($item['emplacement']))
        ) {
            throw new RuntimeException('Le fichier des rayons est invalide.');
        }

        $emplacement = $item['emplacement'] ?? null;
        if ($emplacement !== null && !is_string($emplacement)) {
            throw new RuntimeException('Le fichier des rayons est invalide.');
        }

        return new Rayon($item['nom'], $emplacement, $item['id']);
    }

    /**
     * @param array<int, array{id: int, nom: string, emplacement: ?string}> $data
     */
    private function writeData(array $data): void
    {
        $written = file_put_contents(
            $this->dataFile,
            json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR),
            LOCK_EX
        );
        if ($written === false) {
            throw new RuntimeException('Impossible d\'enregistrer les donnees des rayons.');
        }
    }
}
