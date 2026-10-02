<?php

class Adherent
{
    public function __construct(private PDO $db) {}

    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM adherent')->fetchColumn();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM adherent ORDER BY id_adherent DESC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM adherent WHERE id_adherent = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO adherent (nom, prenom, email, telephone) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$data['nom'], $data['prenom'], $data['email'], $data['telephone']]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE adherent SET nom = ?, prenom = ?, email = ?, telephone = ? WHERE id_adherent = ?');
        return $stmt->execute([$data['nom'], $data['prenom'], $data['email'], $data['telephone'], $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM adherent WHERE id_adherent = ?');
        return $stmt->execute([$id]);
    }
}
