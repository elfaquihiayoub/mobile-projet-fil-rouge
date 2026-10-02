<?php

class RayonApiController
{
    private RayonRepository $repository;

    public function __construct()
    {
        $this->repository = new RayonRepository(Database::connection());
    }

    public function index(): void
    {
        $this->jsonResponse(['success' => true, 'data' => $this->repository->all()]);
    }

    public function show(int $id): void
    {
        $rayon = $this->repository->find($id);

        if ($rayon === null) {
            $this->jsonResponse(['success' => false, 'message' => 'Rayon introuvable.'], 404);
            return;
        }

        $this->jsonResponse(['success' => true, 'data' => $rayon]);
    }

    public function store(): void
    {
        $payload = $this->readBody();

        if (!isset($payload['nom_rayon']) || trim((string) $payload['nom_rayon']) === '') {
            $this->jsonResponse(['success' => false, 'message' => 'Le nom du rayon est requis.'], 400);
            return;
        }

        $rayon = new Rayon();
        $rayon->setNom((string) ($payload['nom_rayon'] ?? ''));
        $rayon->setEmplacement((string) ($payload['emplacement'] ?? ''));

        $created = $this->repository->create($rayon);

        if (!$created) {
            $this->jsonResponse(['success' => false, 'message' => 'Impossible de créer le rayon.'], 500);
            return;
        }

        $this->jsonResponse(['success' => true, 'data' => $this->repository->all(), 'message' => 'Rayon ajoute avec succes.'], 201);
    }

    public function update(int $id): void
    {
        $payload = $this->readBody();

        if (!isset($payload['nom_rayon']) || trim((string) $payload['nom_rayon']) === '') {
            $this->jsonResponse(['success' => false, 'message' => 'Le nom du rayon est requis.'], 400);
            return;
        }

        $existing = $this->repository->find($id);
        if ($existing === null) {
            $this->jsonResponse(['success' => false, 'message' => 'Rayon introuvable.'], 404);
            return;
        }

        $rayon = Rayon::fromArray($existing);
        $rayon->setNom((string) ($payload['nom_rayon'] ?? ''));
        $rayon->setEmplacement((string) ($payload['emplacement'] ?? ''));

        $updated = $this->repository->update($rayon);

        if (!$updated) {
            $this->jsonResponse(['success' => false, 'message' => 'Impossible de modifier le rayon.'], 500);
            return;
        }

        $this->jsonResponse(['success' => true, 'data' => $this->repository->all(), 'message' => 'Rayon modifie avec succes.']);
    }

    public function delete(int $id): void
    {
        $existing = $this->repository->find($id);

        if ($existing === null) {
            $this->jsonResponse(['success' => false, 'message' => 'Rayon introuvable.'], 404);
            return;
        }

        $deleted = $this->repository->delete($id);

        if (!$deleted) {
            $this->jsonResponse(['success' => false, 'message' => 'Impossible de supprimer le rayon.'], 500);
            return;
        }

        $this->jsonResponse(['success' => true, 'data' => $this->repository->all(), 'message' => 'Rayon supprime avec succes.']);
    }

    private function readBody(): array
    {
        $raw = file_get_contents('php://input');

        if ($raw === false || trim($raw) === '') {
            return [];
        }

        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }

    private function jsonResponse(array $payload, int $status = 200): void
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($status);
        echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
