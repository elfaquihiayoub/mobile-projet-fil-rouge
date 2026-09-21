<?php

class Emprunt
{
    public function __construct(private PDO $db) {}

    public function countActive(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM emprunt WHERE date_retour_reelle IS NULL')->fetchColumn();
    }

    public function countLate(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM emprunt WHERE date_retour_reelle IS NULL AND date_retour_prevue < CURDATE()')->fetchColumn();
    }

    public function all(): array
    {
        return $this->db->query("SELECT e.*, a.nom, a.prenom, l.titre FROM emprunt e JOIN adherent a ON a.id_adherent = e.id_adherent JOIN exemplaire_livre l ON l.id_exemplaire = e.id_exemplaire ORDER BY e.id_emprunt DESC")->fetchAll();
    }

    public function active(): array
    {
        return $this->db->query("SELECT e.*, a.nom, a.prenom, l.titre FROM emprunt e JOIN adherent a ON a.id_adherent = e.id_adherent JOIN exemplaire_livre l ON l.id_exemplaire = e.id_exemplaire WHERE e.date_retour_reelle IS NULL ORDER BY e.date_retour_prevue")->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT e.*, a.nom, a.prenom, l.titre FROM emprunt e JOIN adherent a ON a.id_adherent = e.id_adherent JOIN exemplaire_livre l ON l.id_exemplaire = e.id_exemplaire WHERE e.id_emprunt = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO emprunt (date_emprunt, date_retour_prevue, id_adherent, id_exemplaire) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$data['date_emprunt'], $data['date_retour_prevue'], $data['id_adherent'], $data['id_exemplaire']]);
    }

    public function isBookAvailable(int $id): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM emprunt WHERE id_exemplaire = ? AND date_retour_reelle IS NULL');
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn() === 0;
    }

    public function recordReturn(int $id): bool
    {
        $stmt = $this->db->prepare('UPDATE emprunt SET date_retour_reelle = CURDATE() WHERE id_emprunt = ? AND date_retour_reelle IS NULL');
        return $stmt->execute([$id]);
    }
}
