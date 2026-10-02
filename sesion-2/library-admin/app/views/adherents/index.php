<?php $items = $items ?? []; ?>
<div class="page-actions"><a class="btn primary" href="<?= url('adherent', 'create') ?>">+ Ajouter un adherent</a></div>
<?php if (!$items): ?><div class="empty">Aucun adherent trouve.</div><?php else: ?>
<div class="table-wrap"><table><thead><tr><th>Nom</th><th>Prenom</th><th>Email</th><th>Telephone</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($items as $item): ?><tr><td><?= e($item['nom']) ?></td><td><?= e($item['prenom']) ?></td><td><?= e($item['email']) ?></td><td><?= e($item['telephone']) ?></td><td class="actions"><a class="btn sm" href="<?= url('adherent','show',['id'=>$item['id_adherent']]) ?>">Voir</a><a class="btn sm secondary" href="<?= url('adherent','edit',['id'=>$item['id_adherent']]) ?>">Modifier</a><form method="post" action="<?= url('adherent','delete') ?>" data-delete-form><input type="hidden" name="csrf_token" value="<?= csrf_token() ?>"><input type="hidden" name="id" value="<?= e((string)$item['id_adherent']) ?>"><button class="btn sm danger" type="submit">Supprimer</button></form></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?>
