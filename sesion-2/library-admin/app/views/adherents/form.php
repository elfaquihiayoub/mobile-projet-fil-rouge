<?php $item = $item ?? []; $errors = $errors ?? []; ?>
<?php $isEdit = !empty($item['id_adherent']); ?>
<form class="form-card" method="post" action="<?= $isEdit ? url('adherent','update') : url('adherent','store') ?>">
<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>"><?php if ($isEdit): ?><input type="hidden" name="id" value="<?= e((string)$item['id_adherent']) ?>"><?php endif; ?>
<label>Nom<input name="nom" required value="<?= e($item['nom'] ?? '') ?>"></label><span class="field-error"><?= e($errors['nom'] ?? '') ?></span>
<label>Prenom<input name="prenom" required value="<?= e($item['prenom'] ?? '') ?>"></label><span class="field-error"><?= e($errors['prenom'] ?? '') ?></span>
<label>Email<input type="email" name="email" required value="<?= e($item['email'] ?? '') ?>"></label><span class="field-error"><?= e($errors['email'] ?? '') ?></span>
<label>Telephone<input name="telephone" value="<?= e($item['telephone'] ?? '') ?>"></label>
<div class="actions"><a class="btn secondary" href="<?= url('adherent') ?>">Annuler</a><button class="btn primary">Enregistrer</button></div>
</form>
