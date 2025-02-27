<section class="section">
    <div class="container">
        <h1 class="title has-text-centered"><?= $recette->getTitle(); ?></h1>

        <!-- Informations générales -->
        <div class="box">
            <h2 class="subtitle">📌 Informations</h2>
            <p><strong>Catégorie :</strong> <?= ucfirst($recette->getCategory()); ?></p>
            <p><strong>Temps total :</strong> <?= $recette->getTimeTotal(); ?></p>
            <p><strong>Préparation :</strong> <?= $recette->getTimePreparation(); ?></p>
            <p><strong>Cuisson :</strong> <?= $recette->getTimeCuisson(); ?></p>
            <p><strong>Repos :</strong> <?= $recette->getTimeRepos(); ?></p>
            <p><strong>Difficulté :</strong> <?= ucfirst($recette->getDifficulty()); ?></p>
            <p><strong>Coût :</strong> <?= ucfirst($recette->getCost()); ?></p>
        </div>

        <!-- Description -->
        <div class="box">
            <h2 class="subtitle">📝 Description</h2>
            <p><?= $recette->getDescription(); ?></p>
        </div>

        <!-- Ingrédients -->
        <div class="box">
            <h2 class="subtitle">🛒 Ingrédients</h2>
            <?php if (!empty($recette->getIngredients())): ?>
                <ul>
                    <?php foreach ($recette->getIngredients() as $ingredient): ?>
                        <li>
                            <strong><?= $ingredient['name']; ?></strong> :
                            <?= $ingredient['quantity']; ?> <?= $ingredient['unit']; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="has-text-grey">Aucun ingrédient spécifié.</p>
            <?php endif; ?>
        </div>

        <!-- Étapes de préparation -->
        <div class="box">
            <h2 class="subtitle">👨‍🍳 Étapes de préparation</h2>
            <?php if (!empty($recette->getSteps())): ?>
                <ol>
                    <?php foreach ($recette->getSteps() as $step): ?>
                        <li><?= $step; ?></li>
                    <?php endforeach; ?>
                    <br/>
                </ol>
            <?php else: ?>
                <p class="has-text-grey">Aucune étape spécifiée.</p>
            <?php endif; ?>
        </div>

        <!-- Bouton de retour -->
        <div class="has-text-centered">
            <a href="<?= getLink('recette_list');?>" class="button is-link">⬅️ Retour aux recettes</a>
        </div>
    </div>
</section>
