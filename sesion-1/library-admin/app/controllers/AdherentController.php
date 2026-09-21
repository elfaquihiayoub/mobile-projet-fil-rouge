<?php

class AdherentController
{
    private Adherent $model;
    public function __construct() { $this->model = new Adherent(Database::connection()); }
    public function index(): void { render('adherents/index', ['title' => 'Gestion des adherents', 'active' => 'adherents', 'items' => $this->model->all()]); }
    public function show(): void { render('adherents/show', ['title' => 'Detail adherent', 'active' => 'adherents', 'item' => $this->model->find((int)($_GET['id'] ?? 0))]); }
    public function create(): void { render('adherents/form', ['title' => 'Ajouter un adherent', 'active' => 'adherents', 'item' => null, 'errors' => []]); }
    public function store(): void { verify_csrf(); $data = $this->validated(); if (isset($data['errors'])) { render('adherents/form', ['title' => 'Ajouter un adherent', 'active' => 'adherents', 'item' => $_POST, 'errors' => $data['errors']]); return; } $this->model->create($data); flash('success', 'Adherent ajoute avec succes.'); redirect('adherent'); }
    public function edit(): void { render('adherents/form', ['title' => 'Modifier un adherent', 'active' => 'adherents', 'item' => $this->model->find((int)($_GET['id'] ?? 0)), 'errors' => []]); }
    public function update(): void { verify_csrf(); $id = (int)($_POST['id'] ?? 0); $data = $this->validated(); if (isset($data['errors'])) { $data['id_adherent'] = $id; render('adherents/form', ['title' => 'Modifier un adherent', 'active' => 'adherents', 'item' => array_merge($_POST, ['id_adherent' => $id]), 'errors' => $data['errors']]); return; } $this->model->update($id, $data); flash('success', 'Adherent modifie avec succes.'); redirect('adherent'); }
    public function delete(): void { verify_csrf(); $this->model->delete((int)($_POST['id'] ?? 0)); flash('success', 'Adherent supprime avec succes.'); redirect('adherent'); }
    private function validated(): array { $errors = []; $data = ['nom' => trim($_POST['nom'] ?? ''), 'prenom' => trim($_POST['prenom'] ?? ''), 'email' => trim($_POST['email'] ?? ''), 'telephone' => trim($_POST['telephone'] ?? '')]; if ($data['nom'] === '') $errors['nom'] = 'Le nom est requis.'; if ($data['prenom'] === '') $errors['prenom'] = 'Le prenom est requis.'; if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Email invalide.'; return $errors ? ['errors' => $errors] : $data; }
}
