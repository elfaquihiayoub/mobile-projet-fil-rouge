<?php

class Rayon
{
    private ?int $id_rayon;
    private string $nom_rayon;
    private ?string $emplacement;

    public function __construct(string $nom_rayon, ?string $emplacement = null, ?int $id_rayon = null)
    {
        $this->id_rayon = $id_rayon;
        $this->nom_rayon = $nom_rayon;
        $this->emplacement = $emplacement;
    }

    public function getId(): ?int
    {
        return $this->id_rayon;
    }

    public function getNom(): string
    {
        return $this->nom_rayon;
    }

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setNom(string $nom_rayon): void
    {
        $this->nom_rayon = $nom_rayon;
    }

    public function setEmplacement(?string $emplacement): void
    {
        $this->emplacement = $emplacement;
    }
}
