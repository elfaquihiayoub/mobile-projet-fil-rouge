<?php

class Livre
{
    public function __construct(private PDO $db) {}

    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM exemplaire_livre')->fetchColumn();
    }

    public function all(): array
    {
        return $this->db->query('SELECT l.*, r.nom_rayon FROM exemplaire_livre l JOIN rayon r ON r.id_rayon = l.id_rayon ORDER BY l.id_exemplaire DESC')->fetchAll();
    }

    public function available(): array
    {
        return $this->db->query("SELECT l.* FROM exemplaire_livre l WHERE NOT EXISTS (SELECT 1 FROM emprunt e WHERE e.id_exemplaire = l.id_exemplaire AND e.date_retour_reelle IS NULL) ORDER BY l.titre")->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT l.*, r.nom_rayon FROM exemplaire_livre l JOIN rayon r ON r.id_rayon = l.id_rayon WHERE l.id_exemplaire = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO exemplaire_livre (titre, auteur, isbn, etat, id_rayon) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([$data['titre'], $data['auteur'], $data['isbn'], $data['etat'], $data['id_rayon']]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE exemplaire_livre SET titre = ?, auteur = ?, isbn = ?, etat = ?, id_rayon = ? WHERE id_exemplaire = ?');
        return $stmt->execute([$data['titre'], $data['auteur'], $data['isbn'], $data['etat'], $data['id_rayon'], $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM exemplaire_livre WHERE id_exemplaire = ?');
        return $stmt->execute([$id]);
    }
}
