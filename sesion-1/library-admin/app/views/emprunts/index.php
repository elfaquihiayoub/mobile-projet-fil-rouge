<div class="page-actions"><a class="btn primary" href="<?= url('emprunt', 'create') ?>">+ Effectuer un emprunt</a></div>
<?php if (!$items): ?><div class="empty">Aucun emprunt trouve.</div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Adherent</th><th>Livre</th><th>Date emprunt</th><th>Retour prevu</th><th>Retour reel</th><th>Statut</th></tr></thead><tbody>
<?php foreach ($items as $item): ?>
<?php
    $late = !$item['date_retour_reelle'] && $item['date_retour_prevue'] < date('Y-m-d');
    $badgeClass = $late ? 'danger' : ($item['date_retour_reelle'] ? 'success' : 'warning');
    $status = $item['date_retour_reelle'] ? 'Retourne' : ($late ? 'En retard' : 'En cours');
?>
<tr><td><?= e($item['prenom'].' '.$item['nom']) ?></td><td><?= e($item['titre']) ?></td><td><?= e($item['date_emprunt']) ?></td><td><?= e($item['date_retour_prevue']) ?></td><td><?= e($item['date_retour_reelle'] ?: '-') ?></td><td><span class="badge <?= e($badgeClass) ?>"><?= e($status) ?></span></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?>
