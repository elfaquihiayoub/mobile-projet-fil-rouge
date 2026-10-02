<section class="stats-grid">
    <article class="stat-card"><span>Adherents</span><strong><?= e((string)$stats['adherents']) ?></strong><p>Total des adherents</p></article>
    <article class="stat-card"><span>Livres</span><strong><?= e((string)$stats['livres']) ?></strong><p>Total des livres</p></article>
    <article class="stat-card"><span>Emprunts</span><strong><?= e((string)$stats['emprunts']) ?></strong><p>Emprunts en cours</p></article>
    <article class="stat-card warning"><span>Retards</span><strong><?= e((string)$stats['retards']) ?></strong><p>Emprunts en retard</p></article>
</section>
