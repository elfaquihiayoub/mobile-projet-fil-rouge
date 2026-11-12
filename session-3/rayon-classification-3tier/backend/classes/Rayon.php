<?php
declare(strict_types=1);

class Rayon
{
    private ?int $id;
    private string $nom;
    private ?string $emplacement;

    public function __construct(string $nom, ?string $emplacement, ?int $id = null)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->emplacement = $emplacement;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }
}
