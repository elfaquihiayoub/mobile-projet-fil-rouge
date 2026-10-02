<?php

class DashboardController
{
    public function index(): void
    {
        $db = Database::connection();
        render('dashboard/index', [
            'title' => 'Tableau de bord',
            'active' => 'dashboard',
            'stats' => [
                'adherents' => (new Adherent($db))->count(),
                'livres' => (new Livre($db))->count(),
                'emprunts' => (new Emprunt($db))->countActive(),
                'retards' => (new Emprunt($db))->countLate(),
            ],
        ]);
    }
}
