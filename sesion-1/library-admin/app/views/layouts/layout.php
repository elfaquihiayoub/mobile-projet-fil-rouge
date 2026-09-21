<?php $success = flash('success'); $error = flash('error'); ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Bibliotheque') ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-shell">
    <?php require __DIR__ . '/sidebar.php'; ?>
    <div class="main-panel">
        <?php require __DIR__ . '/header.php'; ?>
        <main class="content">
            <?php if ($success): ?><div class="toast success"><?= e($success) ?></div><?php endif; ?>
            <?php if ($error): ?><div class="toast error"><?= e($error) ?></div><?php endif; ?>
            <?php require $viewFile; ?>
        </main>
    </div>
</div>
<div class="modal" id="confirmModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-modal="true">
        <h2>Supprimer cet element ?</h2>
        <p>Cette action est irreversible.</p>
        <div class="actions"><button class="btn secondary" data-close-modal>Annuler</button><button class="btn danger" id="confirmDelete">Supprimer</button></div>
    </div>
</div>
<script src="assets/js/app.js"></script>
</body>
</html>
