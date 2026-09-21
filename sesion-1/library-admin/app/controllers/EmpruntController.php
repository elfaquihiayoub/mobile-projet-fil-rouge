<?php

class EmpruntController
{
    private Emprunt $model; private Adherent $adherents; private Livre $livres;
    public function __construct() { $db = Database::connection(); $this->model = new Emprunt($db); $this->adherents = new Adherent($db); $this->livres = new Livre($db); }
    public function index(): void { render('emprunts/index', ['title' => 'Gestion des emprunts', 'active' => 'emprunts', 'items' => $this->model->all()]); }
    public function create(): void { render('emprunts/form', ['title' => 'Effectuer un emprunt', 'active' => 'emprunts', 'adherents' => $this->adherents->all(), 'livres' => $this->livres->available(), 'errors' => []]); }
    public function store(): void { verify_csrf(); $data = ['id_adherent' => (int)($_POST['id_adherent'] ?? 0), 'id_exemplaire' => (int)($_POST['id_exemplaire'] ?? 0), 'date_emprunt' => $_POST['date_emprunt'] ?? date('Y-m-d'), 'date_retour_prevue' => $_POST['date_retour_prevue'] ?? '']; $errors = []; if (!$this->adherents->find($data['id_adherent'])) $errors['id_adherent'] = 'Adherent invalide.'; if (!$this->livres->find($data['id_exemplaire']) || !$this->model->isBookAvailable($data['id_exemplaire'])) $errors['id_exemplaire'] = 'Livre indisponible ou invalide.'; if ($data['date_emprunt'] === '') $errors['date_emprunt'] = 'Date requise.'; if ($data['date_retour_prevue'] === '') $errors['date_retour_prevue'] = 'Date de retour prevue requise.'; if ($errors) { render('emprunts/form', ['title' => 'Effectuer un emprunt', 'active' => 'emprunts', 'adherents' => $this->adherents->all(), 'livres' => $this->livres->available(), 'errors' => $errors]); return; } $this->model->create($data); flash('success', 'Emprunt enregistre avec succes.'); redirect('emprunt'); }
}
