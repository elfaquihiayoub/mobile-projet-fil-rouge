<?php $item = $item ?? []; $errors = $errors ?? []; ?>
<?php $isEdit = !empty($item['id_rayon']); ?><form class="form-card" method="post" action="<?= $isEdit ? url('rayon','update') : url('rayon','store') ?>">
<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>"><?php if ($isEdit): ?><input type="hidden" name="id" value="<?= e((string)$item['id_rayon']) ?>"><?php endif; ?>
<label>Nom du rayon<input name="nom_rayon" required value="<?= e($item['nom_rayon'] ?? '') ?>"></label><span class="field-error"><?= e($errors['nom_rayon'] ?? '') ?></span>
<label>Emplacement<input name="emplacement" value="<?= e($item['emplacement'] ?? '') ?>"></label>
<div class="actions"><a class="btn secondary" href="<?= url('rayon') ?>">Annuler</a><button class="btn primary">Enregistrer</button></div></form>
