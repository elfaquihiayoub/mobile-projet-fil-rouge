<?php $isEdit = !empty($item['id_exemplaire']); ?><form class="form-card" method="post" action="<?= $isEdit ? url('livre','update') : url('livre','store') ?>">
<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>"><?php if ($isEdit): ?><input type="hidden" name="id" value="<?= e((string)$item['id_exemplaire']) ?>"><?php endif; ?>
<label>Titre<input name="titre" required value="<?= e($item['titre'] ?? '') ?>"></label><span class="field-error"><?= e($errors['titre'] ?? '') ?></span>
<label>Auteur<input name="auteur" required value="<?= e($item['auteur'] ?? '') ?>"></label><span class="field-error"><?= e($errors['auteur'] ?? '') ?></span>
<label>ISBN<input name="isbn" value="<?= e($item['isbn'] ?? '') ?>"></label>
<label>Etat<input name="etat" required value="<?= e($item['etat'] ?? '') ?>"></label><span class="field-error"><?= e($errors['etat'] ?? '') ?></span>
<label>Rayon<select name="id_rayon" required><option value="">Choisir un rayon</option><?php foreach ($rayons as $rayon): ?><option value="<?= e((string)$rayon['id_rayon']) ?>" <?= (int)($item['id_rayon'] ?? 0) === (int)$rayon['id_rayon'] ? 'selected' : '' ?>><?= e($rayon['nom_rayon']) ?></option><?php endforeach; ?></select></label><span class="field-error"><?= e($errors['id_rayon'] ?? '') ?></span>
<div class="actions"><a class="btn secondary" href="<?= url('livre') ?>">Annuler</a><button class="btn primary">Enregistrer</button></div></form>
