<?php $item = $item ?? null; ?>
<?php if (!$item): ?><div class="empty">Rayon introuvable.</div><?php else: ?><div class="detail-card"><p><strong>Nom du rayon</strong><?= e($item['nom_rayon']) ?></p><p><strong>Emplacement</strong><?= e($item['emplacement']) ?></p><a class="btn secondary" href="<?= url('rayon') ?>">Retour</a></div><?php endif; ?>
