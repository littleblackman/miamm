<section class="hero is-medium">
    <div class="hero-body">
        <div class="container has-text-centered">
            <h1 class="title">
                🍽️ Bienvenue sur Miamm !
            </h1>
            <p class="subtitle">
                Le secret des petites recettes futées
            </p>
            <a href="<?= getLink('recettes');?>" class="button is-light is-large">
                🔎 Explorer les recettes
            </a>
            <a href="<?= getLink('recette_create');?>" class="button is-warning is-large">
                ➕ Ajouter une recette
            </a>
        </div>
    </div>
</section>
<section class="section">
    <div class="container">
        <h2 class="title has-text-centered">🔥 Recettes les plus populaires</h2>
        <div class="columns is-multiline">
            <!-- Exemple de recette -->
            <div class="column is-4">
                <div class="card">
                    <div class="card-image">
                        <figure class="image is-4by3">
                            <img src="/images/recette1.jpg" alt="Nom de la recette">
                        </figure>
                    </div>
                    <div class="card-content">
                        <h3 class="title is-5">🍕 Pizza maison</h3>
                        <p>Une délicieuse pizza faite maison avec une pâte croustillante.</p>
                        <a href="/recette/1" class="button is-primary is-small">Voir la recette</a>
                    </div>
                </div>
            </div>
            <!-- Ajouter d'autres recettes dynamiquement ici -->
        </div>
    </div>
</section>
