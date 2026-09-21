<?php

class LivreController
{
    private Livre $model; private Rayon $rayons;
    public function __construct() { $db = Database::connection(); $this->model = new Livre($db); $this->rayons = new Rayon($db); }
    public function index(): void { render('livres/index', ['title' => 'Gestion des livres', 'active' => 'livres', 'items' => $this->model->all()]); }
    public function show(): void { render('livres/show', ['title' => 'Detail livre', 'active' => 'livres', 'item' => $this->model->find((int)($_GET['id'] ?? 0))]); }
    public function create(): void { render('livres/form', ['title' => 'Ajouter un livre', 'active' => 'livres', 'item' => null, 'rayons' => $this->rayons->all(), 'errors' => []]); }
    public function store(): void { verify_csrf(); $data = $this->validated(); if (isset($data['errors'])) { render('livres/form', ['title' => 'Ajouter un livre', 'active' => 'livres', 'item' => $_POST, 'rayons' => $this->rayons->all(), 'errors' => $data['errors']]); return; } $this->model->create($data); flash('success', 'Livre ajoute avec succes.'); redirect('livre'); }
    public function edit(): void { render('livres/form', ['title' => 'Modifier un livre', 'active' => 'livres', 'item' => $this->model->find((int)($_GET['id'] ?? 0)), 'rayons' => $this->rayons->all(), 'errors' => []]); }
    public function update(): void { verify_csrf(); $id = (int)($_POST['id'] ?? 0); $data = $this->validated(); if (isset($data['errors'])) { render('livres/form', ['title' => 'Modifier un livre', 'active' => 'livres', 'item' => array_merge($_POST, ['id_exemplaire' => $id]), 'rayons' => $this->rayons->all(), 'errors' => $data['errors']]); return; } $this->model->update($id, $data); flash('success', 'Livre modifie avec succes.'); redirect('livre'); }
    public function delete(): void { verify_csrf(); $this->model->delete((int)($_POST['id'] ?? 0)); flash('success', 'Livre supprime avec succes.'); redirect('livre'); }
    private function validated(): array { $errors = []; $data = ['titre' => trim($_POST['titre'] ?? ''), 'auteur' => trim($_POST['auteur'] ?? ''), 'isbn' => trim($_POST['isbn'] ?? ''), 'etat' => trim($_POST['etat'] ?? ''), 'id_rayon' => (int)($_POST['id_rayon'] ?? 0)]; foreach (['titre' => 'Le titre est requis.', 'auteur' => 'L auteur est requis.', 'etat' => 'L etat est requis.'] as $k => $m) if ($data[$k] === '') $errors[$k] = $m; if ($data['id_rayon'] <= 0) $errors['id_rayon'] = 'Le rayon est requis.'; return $errors ? ['errors' => $errors] : $data; }
}
