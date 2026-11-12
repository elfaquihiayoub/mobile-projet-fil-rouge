<?php

require_once __DIR__ . '/../classes/Rayon.php';

class RayonDAO
{
    private string $dataFile;

    public function __construct(?string $dataFile = null)
    {
        if ($dataFile === null) {
            $this->dataFile = __DIR__ . '/../data/rayon.json';
        } else {
            $this->dataFile = $dataFile;
        }
    }

    public function getAll(): array
    {
        $rayons = [];
        foreach ($this->readData() as $item) {
            $rayons[] = $this->hydrate($item);
        }

        return $rayons;
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
            if ($item['id'] >= $id) {
                $id = $item['id'] + 1;
            }
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

        foreach ($data as $index => $item) {
            if ($item['id'] === $id) {
                $data[$index] = [
                    'id' => $id,
                    'nom' => $rayon->getNom(),
                    'emplacement' => $rayon->getEmplacement(),
                ];
                $this->writeData($data);
                return true;
            }
        }

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

    private function readData(): array
    {
        $jsonData = file_get_contents($this->dataFile);
        if ($jsonData === false) {
            throw new RuntimeException('Impossible de lire les donnees des rayons.');
        }

        $data = json_decode($jsonData, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
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

    private function hydrate(array $item): Rayon
    {
        if (!isset($item['id'], $item['nom']) || !is_int($item['id']) || !is_string($item['nom'])) {
            throw new RuntimeException('Le fichier des rayons est invalide.');
        }

        $emplacement = null;
        if (isset($item['emplacement'])) {
            $emplacement = $item['emplacement'];
        }

        if ($emplacement !== null && !is_string($emplacement)) {
            throw new RuntimeException('Le fichier des rayons est invalide.');
        }

        return new Rayon($item['nom'], $emplacement, $item['id']);
    }

    private function writeData(array $data): void
    {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        if ($jsonData === false) {
            throw new RuntimeException('Impossible de convertir les donnees des rayons en JSON.');
        }

        $written = file_put_contents($this->dataFile, $jsonData, LOCK_EX);
        if ($written === false) {
            throw new RuntimeException('Impossible d\'enregistrer les donnees des rayons.');
        }
    }
}
