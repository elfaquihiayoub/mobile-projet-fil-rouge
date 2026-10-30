<?php

require_once __DIR__ . '/../../classes/GestionRayon.php';

header('Content-Type: application/json; charset=utf-8');

class ControllerRayon
{
    public function listAll(): void
    {
        $gestionRayon = new GestionRayon();
        $rayons = $gestionRayon->getAll();
        $result = [];

        foreach ($rayons as $rayon) {
            $result[] = [
                'id' => $rayon->getId(),
                'nom' => $rayon->getNom(),
                'emplacement' => $rayon->getEmplacement(),
            ];
        }

        echo json_encode($result, JSON_THROW_ON_ERROR);
    }

    public function createRayon(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Requete JSON invalide.']);
            return;
        }

        $nom = trim((string)($data['nom'] ?? ''));
        $emplacement = trim((string)($data['emplacement'] ?? ''));

        if ($nom === '') {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Le nom du rayon est requis.']);
            return;
        }

        $rayon = new Rayon($nom, $emplacement !== '' ? $emplacement : null);
        $gestionRayon = new GestionRayon();
        $id = $gestionRayon->create($rayon);

        echo json_encode([
            'success' => true,
            'data' => [
                'id' => $id,
                'nom' => $rayon->getNom(),
                'emplacement' => $rayon->getEmplacement(),
            ],
        ], JSON_THROW_ON_ERROR);
    }
}

try {
    $controller = new ControllerRayon();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->createRayon();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller->listAll();
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Methode non autorisee.']);
    }
} catch (RuntimeException | JsonException $error) {
    error_log($error->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur lors du traitement des rayons.']);
}
