<?php

class Rayon
{
    private ?int $id = null;
    private string $nom = '';
    private string $emplacement = '';

    public function __construct(?int $id = null, ?string $nom = null, ?string $emplacement = null)
    {
        if ($id !== null) {
            $this->id = $id;
        }

        if ($nom !== null) {
            $this->nom = $nom;
        }

        if ($emplacement !== null) {
            $this->emplacement = $emplacement;
        }
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['id_rayon']) ? (int) $data['id_rayon'] : null,
            $data['nom_rayon'] ?? '',
            $data['emplacement'] ?? ''
        );
    }

    public function toArray(): array
    {
        return [
            'id_rayon' => $this->id,
            'nom_rayon' => $this->nom,
            'emplacement' => $this->emplacement,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = trim($nom);
    }

    public function getEmplacement(): string
    {
        return $this->emplacement;
    }

    public function setEmplacement(string $emplacement): void
    {
        $this->emplacement = trim($emplacement);
    }
}
