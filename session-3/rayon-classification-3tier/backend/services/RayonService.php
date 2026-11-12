<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/RayonDAO.php';

class RayonService extends BaseService
{
    public function __construct(private RayonDAO $rayonDAO)
    {
    }

    /**
     * @return Rayon[]
     */
    public function getAll(): array
    {
        return $this->rayonDAO->getAll();
    }

    public function getById(int $id): ?Rayon
    {
        return $this->rayonDAO->findById($id);
    }

    public function create(string $nom, ?string $emplacement): Rayon
    {
        $rayon = new Rayon(
            $this->requiredText($nom, 'nom'),
            $this->optionalText($emplacement)
        );
        $id = $this->rayonDAO->create($rayon);

        return new Rayon($rayon->getNom(), $rayon->getEmplacement(), $id);
    }

    public function update(int $id, string $nom, ?string $emplacement): ?Rayon
    {
        $rayon = new Rayon(
            $this->requiredText($nom, 'nom'),
            $this->optionalText($emplacement),
            $id
        );

        return $this->rayonDAO->update($id, $rayon) ? $rayon : null;
    }

    public function delete(int $id): bool
    {
        return $this->rayonDAO->delete($id);
    }
}
