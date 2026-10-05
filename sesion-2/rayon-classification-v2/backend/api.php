<?php

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/classes/Rayon.php';
require_once __DIR__ . '/classes/RayonRepository.php';

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=gestion_bibliotheque;charset=utf8mb4',
        'root',
        'ayoub.fh2k52k5',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    $repository = new RayonRepository($pdo);
    $rawBody = file_get_contents('php://input');
    $input = $rawBody !== '' ? json_decode($rawBody, true) : [];
    $input = is_array($input) ? $input : [];

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $rayons = $repository->getAll();
            $result = [];

            foreach ($rayons as $rayon) {
                $result[] = [
                    'id_rayon' => $rayon->getId(),
                    'nom_rayon' => $rayon->getNom(),
                    'emplacement' => $rayon->getEmplacement(),
                ];
            }

            echo json_encode($result);
            break;

        case 'POST':
            $nomRayon = trim((string)($input['nom_rayon'] ?? ''));
            $emplacement = trim((string)($input['emplacement'] ?? ''));

            if ($nomRayon === '') {
                http_response_code(422);
                echo json_encode(['success' => false, 'message' => 'Le nom du rayon est requis.']);
                exit;
            }

            $rayon = new Rayon($nomRayon, $emplacement);
            $repository->create($rayon);

            echo json_encode([
                'success' => true,
                'message' => 'Rayon ajoute avec succes.',
                'data' => [
                    'id_rayon' => $pdo->lastInsertId(),
                    'nom_rayon' => $rayon->getNom(),
                    'emplacement' => $rayon->getEmplacement(),
                ],
            ]);
            break;

        case 'PUT':
            $idRayon = (int)($input['id_rayon'] ?? 0);
            $nomRayon = trim((string)($input['nom_rayon'] ?? ''));
            $emplacement = trim((string)($input['emplacement'] ?? ''));

            if ($idRayon <= 0 || $nomRayon === '') {
                http_response_code(422);
                echo json_encode(['success' => false, 'message' => 'Informations invalides pour la modification.']);
                exit;
            }

            $rayon = new Rayon($nomRayon, $emplacement, $idRayon);
            $repository->update($rayon);

            echo json_encode([
                'success' => true,
                'message' => 'Rayon modifie avec succes.',
                'data' => [
                    'id_rayon' => $idRayon,
                    'nom_rayon' => $nomRayon,
                    'emplacement' => $emplacement,
                ],
            ]);
            break;

        case 'DELETE':
            $idRayon = (int)($input['id_rayon'] ?? 0);

            if ($idRayon <= 0) {
                http_response_code(422);
                echo json_encode(['success' => false, 'message' => 'Identifiant du rayon invalide.']);
                exit;
            }

            $repository->delete($idRayon);

            echo json_encode([
                'success' => true,
                'message' => 'Rayon supprime avec succes.',
                'data' => ['id_rayon' => $idRayon],
            ]);
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Methode non autorisee.']);
            break;
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur serveur : ' . $e->getMessage(),
    ]);
}
