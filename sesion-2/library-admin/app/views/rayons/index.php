<?php $items = $items ?? []; ?>
<div class="page-actions"><button type="button" class="btn primary" data-open-rayon-form>+ Ajouter un rayon</button></div>
<div class="form-card" id="rayonFormWrapper" style="display:none;">
    <form id="rayonForm" data-rayon-form>
        <input type="hidden" name="id" id="rayonId" value="">
        <label>Nom du rayon<input id="nom_rayon" name="nom_rayon" required></label>
        <label>Emplacement<input id="emplacement" name="emplacement"></label>
        <div class="actions">
            <button type="button" class="btn secondary" data-reset-rayon-form>Annuler</button>
            <button class="btn primary">Enregistrer</button>
        </div>
    </form>
</div>
<?php if (!$items): ?><div class="empty">Aucun rayon trouve.</div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Nom du rayon</th><th>Emplacement</th><th>Actions</th></tr></thead><tbody id="rayonTableBody">
<?php foreach ($items as $item): ?><tr><td><?= e($item['nom_rayon']) ?></td><td><?= e($item['emplacement']) ?></td><td class="actions"><a class="btn sm" href="<?= url('rayon','show',['id'=>$item['id_rayon']]) ?>">Voir</a><button type="button" class="btn sm secondary" data-edit-rayon="<?= e((string)$item['id_rayon']) ?>">Modifier</button><button type="button" class="btn sm danger" data-delete-rayon="<?= e((string)$item['id_rayon']) ?>">Supprimer</button></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?>
