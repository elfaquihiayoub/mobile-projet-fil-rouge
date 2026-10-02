<?php

class RayonController
{
    private RayonRepository $repository;

    public function __construct()
    {
        $this->repository = new RayonRepository(Database::connection());
    }

    public function index(): void
    {
        render('rayons/index', ['title' => 'Gestion des rayons', 'active' => 'rayons', 'items' => $this->repository->all()]);
    }

    public function show(): void
    {
        render('rayons/show', ['title' => 'Detail rayon', 'active' => 'rayons', 'item' => $this->repository->find((int)($_GET['id'] ?? 0))]);
    }

    public function create(): void
    {
        render('rayons/form', ['title' => 'Ajouter un rayon', 'active' => 'rayons', 'item' => null, 'errors' => []]);
    }

    public function store(): void
    {
        verify_csrf();
        $data = $this->validated();

        if (isset($data['errors'])) {
            render('rayons/form', ['title' => 'Ajouter un rayon', 'active' => 'rayons', 'item' => $_POST, 'errors' => $data['errors']]);
            return;
        }

        $rayon = new Rayon();
        $rayon->setNom($data['nom_rayon']);
        $rayon->setEmplacement($data['emplacement']);

        $this->repository->create($rayon);
        flash('success', 'Rayon ajoute avec succes.');
        redirect('rayon');
    }

    public function edit(): void
    {
        render('rayons/form', ['title' => 'Modifier un rayon', 'active' => 'rayons', 'item' => $this->repository->find((int)($_GET['id'] ?? 0)), 'errors' => []]);
    }

    public function update(): void
    {
        verify_csrf();
        $id = (int)($_POST['id'] ?? 0);
        $data = $this->validated();

        if (isset($data['errors'])) {
            render('rayons/form', ['title' => 'Modifier un rayon', 'active' => 'rayons', 'item' => array_merge($_POST, ['id_rayon' => $id]), 'errors' => $data['errors']]);
            return;
        }

        $rayon = new Rayon($id, $data['nom_rayon'], $data['emplacement']);
        $this->repository->update($rayon);
        flash('success', 'Rayon modifie avec succes.');
        redirect('rayon');
    }

    public function delete(): void
    {
        verify_csrf();
        $this->repository->delete((int)($_POST['id'] ?? 0));
        flash('success', 'Rayon supprime avec succes.');
        redirect('rayon');
    }

    private function validated(): array
    {
        $data = ['nom_rayon' => trim($_POST['nom_rayon'] ?? ''), 'emplacement' => trim($_POST['emplacement'] ?? '')];
        return $data['nom_rayon'] === '' ? ['errors' => ['nom_rayon' => 'Le nom du rayon est requis.']] : $data;
    }
}
