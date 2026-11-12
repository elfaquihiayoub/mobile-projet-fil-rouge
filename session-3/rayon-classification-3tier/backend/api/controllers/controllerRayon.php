<?php
declare(strict_types=1);

require_once __DIR__ . '/../../services/RayonService.php';

class ControllerRayon
{
    public function __construct(private ?RayonService $rayonService = null)
    {
        $this->rayonService ??= new RayonService(new RayonDAO());
    }

    public function handle(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $method = $_SERVER['REQUEST_METHOD'] ?? '';

            if ($method === 'GET') {
                $this->respond($this->serializeRayons($this->rayonService->getAll()));
                return;
            }

            if ($method === 'POST') {
                $fields = $this->readFields();
                $rayon = $this->rayonService->create($fields['nom'], $fields['emplacement']);
                http_response_code(201);
                $this->respond(['success' => true, 'data' => $this->serializeRayon($rayon)]);
                return;
            }

            if ($method === 'PUT') {
                $id = $this->readId();
                $fields = $this->readFields();
                $rayon = $this->rayonService->update($id, $fields['nom'], $fields['emplacement']);
                if ($rayon === null) {
                    $this->respondError(404, 'Rayon introuvable.');
                    return;
                }

                $this->respond(['success' => true, 'data' => $this->serializeRayon($rayon)]);
                return;
            }

            if ($method === 'DELETE') {
                $id = $this->readId();
                if (!$this->rayonService->delete($id)) {
                    $this->respondError(404, 'Rayon introuvable.');
                    return;
                }

                $this->respond(['success' => true]);
                return;
            }

            header('Allow: GET, POST, PUT, DELETE');
            $this->respondError(405, 'Methode non autorisee.');
        } catch (UnexpectedValueException $error) {
            $this->respondError(400, $error->getMessage());
        } catch (InvalidArgumentException $error) {
            $this->respondError(422, $error->getMessage());
        } catch (RuntimeException | JsonException $error) {
            error_log($error->getMessage());
            $this->respondError(500, 'Erreur lors du traitement des rayons.');
        }
    }

    /**
     * @param Rayon[] $rayons
     * @return array<int, array{id: ?int, nom: string, emplacement: ?string}>
     */
    private function serializeRayons(array $rayons): array
    {
        return array_map(fn (Rayon $rayon): array => $this->serializeRayon($rayon), $rayons);
    }

    /**
     * @return array{id: ?int, nom: string, emplacement: ?string}
     */
    private function serializeRayon(Rayon $rayon): array
    {
        return [
            'id' => $rayon->getId(),
            'nom' => $rayon->getNom(),
            'emplacement' => $rayon->getEmplacement(),
        ];
    }

    /**
     * @return array{nom: string, emplacement: ?string}
     */
    private function readFields(): array
    {
        $rawBody = file_get_contents('php://input');
        if ($rawBody === false || trim($rawBody) === '') {
            throw new UnexpectedValueException('Requete JSON invalide.');
        }

        try {
            $data = json_decode($rawBody, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $error) {
            throw new UnexpectedValueException('Requete JSON invalide.', 0, $error);
        }

        if (!is_array($data) || !str_starts_with(ltrim($rawBody), '{')) {
            throw new UnexpectedValueException('Requete JSON invalide.');
        }
        if (!isset($data['nom']) || !is_string($data['nom'])) {
            throw new InvalidArgumentException('Le nom du rayon est requis.');
        }

        $emplacement = $data['emplacement'] ?? null;
        if ($emplacement !== null && !is_string($emplacement)) {
            throw new InvalidArgumentException('Emplacement invalide.');
        }

        return ['nom' => $data['nom'], 'emplacement' => $emplacement];
    }

    private function readId(): int
    {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id === null || $id < 1) {
            throw new UnexpectedValueException('Identifiant de rayon invalide.');
        }

        return $id;
    }

    private function respondError(int $status, string $message): void
    {
        http_response_code($status);
        $this->respond(['success' => false, 'message' => $message]);
    }

    private function respond(array $data): void
    {
        echo json_encode($data, JSON_THROW_ON_ERROR);
    }
}

if (isset($_SERVER['SCRIPT_FILENAME']) && realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) {
    (new ControllerRayon())->handle();
}
