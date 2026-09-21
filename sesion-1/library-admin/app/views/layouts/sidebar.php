<?php $nav = ['dashboard' => ['Tableau de bord', 'dashboard'], 'adherents' => ['Adherents', 'adherent'], 'rayons' => ['Rayons', 'rayon'], 'livres' => ['Livres', 'livre'], 'emprunts' => ['Emprunts', 'emprunt'], 'retours' => ['Retours', 'retour']]; ?>
<aside class="sidebar" id="sidebar">
    <a class="brand" href="<?= url() ?>"><span class="brand-mark">B</span><span>Bibliotheque</span></a>
    <nav>
        <?php foreach ($nav as $key => [$label, $controller]): ?>
            <a class="<?= ($active ?? '') === $key ? 'active' : '' ?>" href="<?= url($controller) ?>"><?= e($label) ?></a>
        <?php endforeach; ?>
    </nav>
    <div class="sidebar-bottom"><strong>Administrateur</strong><a href="#">Deconnexion</a></div>
</aside>
