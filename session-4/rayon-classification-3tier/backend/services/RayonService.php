<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/RayonDAO.php';

class RayonService extends BaseService
{
    private RayonDAO $rayonDAO;

    public function __construct(RayonDAO $rayonDAO)
    {
        $this->rayonDAO = $rayonDAO;
    }

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
        $nom = $this->requiredText($nom, 'nom');
        $emplacement = $this->optionalText($emplacement);

        $rayon = new Rayon($nom, $emplacement);
        $id = $this->rayonDAO->create($rayon);

        return new Rayon($nom, $emplacement, $id);
    }

    public function update(int $id, string $nom, ?string $emplacement): ?Rayon
    {
        $nom = $this->requiredText($nom, 'nom');
        $emplacement = $this->optionalText($emplacement);
        $rayon = new Rayon($nom, $emplacement, $id);

        if (!$this->rayonDAO->update($id, $rayon)) {
            return null;
        }

        return $rayon;
    }

    public function delete(int $id): bool
    {
        return $this->rayonDAO->delete($id);
    }
}
