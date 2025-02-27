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
        <h2 class="title has-text-centered">🔥 Les dernières recettes</h2>
        <div class="columns is-multiline">
            <?php foreach($latest as $recette):?>
                <div class="column is-4">
                    <div class="card">
                        <div class="card-content">
                            <h3 class="title is-5"><?= $recette->getTitle();?></h3>
                            <p><?= $recette->getDescription();?></p>
                            <a href="<?= getLink('recette_show', ['id' => $recette->getId()]);?>" class="button is-primary is-small">Voir la recette</a>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</section>
