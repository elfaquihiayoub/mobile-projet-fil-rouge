<?php $adherents = $adherents ?? []; $livres = $livres ?? []; $errors = $errors ?? []; ?>
<form class="form-card" method="post" action="<?= url('emprunt','store') ?>">
<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
<label>Adherent<select name="id_adherent" required><option value="">Choisir un adherent</option><?php foreach ($adherents as $a): ?><option value="<?= e((string)$a['id_adherent']) ?>"><?= e($a['prenom'].' '.$a['nom']) ?></option><?php endforeach; ?></select></label><span class="field-error"><?= e($errors['id_adherent'] ?? '') ?></span>
<label>Livre<select name="id_exemplaire" required><option value="">Choisir un livre disponible</option><?php foreach ($livres as $l): ?><option value="<?= e((string)$l['id_exemplaire']) ?>"><?= e($l['titre']) ?></option><?php endforeach; ?></select></label><span class="field-error"><?= e($errors['id_exemplaire'] ?? '') ?></span>
<label>Date emprunt<input type="date" name="date_emprunt" required value="<?= date('Y-m-d') ?>"></label><span class="field-error"><?= e($errors['date_emprunt'] ?? '') ?></span>
<label>Date retour prevue<input type="date" name="date_retour_prevue" required></label><span class="field-error"><?= e($errors['date_retour_prevue'] ?? '') ?></span>
<div class="actions"><a class="btn secondary" href="<?= url('emprunt') ?>">Annuler</a><button class="btn primary">Enregistrer</button></div></form>
