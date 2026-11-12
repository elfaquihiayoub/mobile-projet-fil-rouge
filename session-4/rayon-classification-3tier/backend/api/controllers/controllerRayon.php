<?php

require_once __DIR__ . '/../../services/RayonService.php';

class ControllerRayon
{
    private RayonService $rayonService;

    public function __construct(?RayonService $rayonService = null)
    {
        if ($rayonService === null) {
            $rayonService = new RayonService(new RayonDAO());
        }

        $this->rayonService = $rayonService;
    }

    public function handle(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $method = '';
            if (isset($_SERVER['REQUEST_METHOD'])) {
                $method = $_SERVER['REQUEST_METHOD'];
            }

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
        } catch (RuntimeException $error) {
            error_log($error->getMessage());
            $this->respondError(500, 'Erreur lors du traitement des rayons.');
        }
    }

    private function serializeRayons(array $rayons): array
    {
        $result = [];
        foreach ($rayons as $rayon) {
            $result[] = $this->serializeRayon($rayon);
        }

        return $result;
    }

    private function serializeRayon(Rayon $rayon): array
    {
        return [
            'id' => $rayon->getId(),
            'nom' => $rayon->getNom(),
            'emplacement' => $rayon->getEmplacement(),
        ];
    }

    private function readFields(): array
    {
        $rawBody = file_get_contents('php://input');
        if ($rawBody === false || trim($rawBody) === '') {
            throw new UnexpectedValueException('Requete JSON invalide.');
        }

        $data = json_decode($rawBody);
        if (json_last_error() !== JSON_ERROR_NONE || !is_object($data)) {
            throw new UnexpectedValueException('Requete JSON invalide.');
        }

        if (!isset($data->nom) || !is_string($data->nom)) {
            throw new InvalidArgumentException('Le nom du rayon est requis.');
        }

        $emplacement = null;
        if (isset($data->emplacement)) {
            $emplacement = $data->emplacement;
        }

        if ($emplacement !== null && !is_string($emplacement)) {
            throw new InvalidArgumentException('Emplacement invalide.');
        }

        return ['nom' => $data->nom, 'emplacement' => $emplacement];
    }

    private function readId(): int
    {
        $value = null;
        if (isset($_GET['id'])) {
            $value = $_GET['id'];
        }

        $id = filter_var($value, FILTER_VALIDATE_INT);
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
        $json = json_encode($data);
        if ($json === false) {
            throw new RuntimeException('Impossible de creer la reponse JSON.');
        }

        echo $json;
    }
}

if (isset($_SERVER['SCRIPT_FILENAME']) && realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) {
    $controller = new ControllerRayon();
    $controller->handle();
}
