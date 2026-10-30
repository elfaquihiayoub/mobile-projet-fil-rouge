<?php

require_once __DIR__ . '/Rayon.php';

class GestionRayon
{
    public static string $dataFile = __DIR__ . '/../data/rayon.json';

    public function getAll(): array
    {
        $jsonData = file_get_contents(self::$dataFile);
        if ($jsonData === false) {
            throw new RuntimeException('Impossible de lire les donnees des rayons.');
        }

        $data = json_decode($jsonData, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($data)) {
            throw new RuntimeException('Le fichier des rayons est invalide.');
        }

        $rayons = [];

        foreach ($data as $item) {
            $rayons[] = new Rayon(
                $item['nom'],
                $item['emplacement'],
                $item['id']
            );
        }

        return $rayons;
    }

    public function create(Rayon $rayon): int
    {
        $jsonData = file_get_contents(self::$dataFile);
        if ($jsonData === false) {
            throw new RuntimeException('Impossible de lire les donnees des rayons.');
        }

        $data = json_decode($jsonData, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($data)) {
            throw new RuntimeException('Le fichier des rayons est invalide.');
        }

        $id = 1;

        foreach ($data as $item) {
            $id = max($id, $item['id'] + 1);
        }

        $data[] = [
            'id' => $id,
            'nom' => $rayon->getNom(),
            'emplacement' => $rayon->getEmplacement(),
        ];

        $written = file_put_contents(
            self::$dataFile,
            json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR),
            LOCK_EX
        );
        if ($written === false) {
            throw new RuntimeException('Impossible d\'enregistrer le rayon.');
        }

        return $id;
    }
}
