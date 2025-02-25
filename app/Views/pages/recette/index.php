<section class="section">
    <div class="container">
        <h1 class="title has-text-centered">🍽️ Nos Recettes</h1>

        <!-- Grille des recettes -->
        <div class="columns is-multiline">
            <?php foreach ($recettes as $recette) : ?>
                <div class="column is-4">
                    <div class="card">
                        <div class="card-content">
                            <h3 class="title is-5"><?= htmlspecialchars($recette->getTitre()) ?></h3>
                            <p><?= htmlspecialchars($recette->getDescription()) ?></p>
                            <a href="<?= getLink('recette_show', ['id' => $recette->getId(), 'date' => $recette->getCreatedAt()]) ?>"
                               class="button is-primary is-small">Voir la recette</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
