<?php

class RetourController
{
    private Emprunt $model;
    public function __construct() { $this->model = new Emprunt(Database::connection()); }
    public function index(): void { render('retours/index', ['title' => 'Gestion des retours', 'active' => 'retours', 'items' => $this->model->active()]); }
    public function confirm(): void { render('retours/confirm', ['title' => 'Confirmer le retour', 'active' => 'retours', 'item' => $this->model->find((int)($_GET['id'] ?? 0))]); }
    public function store(): void { verify_csrf(); $this->model->recordReturn((int)($_POST['id'] ?? 0)); flash('success', 'Retour enregistre avec succes.'); redirect('retour'); }
}
