<form action="<?= getLink('recette_update', ['id' => $recette->getId()]);?>" method="POST">
    <input type="hidden" name="id" value="<?= $recette->getId() ?>">

    <div class="field">
        <label class="label" for="title">Titre :</label>
        <div class="control">
            <input class="input" type="text" id="title" name="title" value="<?= htmlspecialchars($recette->getTitle()) ?>" required>
        </div>
    </div>

    <div class="field">
        <label class="label" for="description">Description :</label>
        <div class="control">
            <textarea class="textarea" id="description" name="description" required><?= htmlspecialchars($recette->getDescription()) ?></textarea>
        </div>
    </div>

    <div class="field">
        <label class="label" for="category">Catégorie :</label>
        <div class="control">
            <div class="select">
                <select id="category" name="category">
                    <option value="plat" <?= $recette->getCategory() == 'plat' ? 'selected' : '' ?>>Plat</option>
                    <option value="entrée" <?= $recette->getCategory() == 'entrée' ? 'selected' : '' ?>>Entrée</option>
                    <option value="dessert" <?= $recette->getCategory() == 'dessert' ? 'selected' : '' ?>>Dessert</option>
                </select>
            </div>
        </div>
    </div>

    <div class="field">
        <label class="label">Temps de préparation :</label>
        <div class="control">
            <input class="input" type="text" name="time_preparation" value="<?= htmlspecialchars($recette->getTimePreparation()) ?>" required>
        </div>
    </div>

    <div class="field">
        <label class="label">Temps de repos :</label>
        <div class="control">
            <input class="input" type="text" name="time_repos" value="<?= htmlspecialchars($recette->getTimeRepos()) ?>">
        </div>
    </div>

    <div class="field">
        <label class="label">Temps de cuisson :</label>
        <div class="control">
            <input class="input" type="text" name="time_cuisson" value="<?= htmlspecialchars($recette->getTimeCuisson()) ?>" required>
        </div>
    </div>

    <div class="field">
        <label class="label">Difficulté :</label>
        <div class="control">
            <input class="input" type="text" name="difficulty" value="<?= htmlspecialchars($recette->getDifficulty()) ?>" required>
        </div>
    </div>

    <div class="field">
        <label class="label">Coût :</label>
        <div class="control">
            <input class="input" type="text" name="cost" value="<?= htmlspecialchars($recette->getCost()) ?>" required>
        </div>
    </div>

    <div class="field">
        <label class="label">Étapes :</label>
        <div class="control">
            <?php foreach ($recette->getSteps() as $index => $step) : ?>
                <div class="field">
                    <input class="input" type="text" name="steps[]" value="<?= htmlspecialchars($step) ?>">
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="field">
        <label class="label">Ingrédients :</label>
        <div class="control">
            <?php foreach ($recette->getIngredients() as $ingredient) : ?>

                <div class="field is-grouped">
                    <div class="control">
                        <input class="input" type="text" name="ingredients[<?= $ingredient['id'] ?>][name]" value="<?= $ingredient['name'] ?>" readonly>
                    </div>
                    <div class="control">
                        <input class="input" type="text" name="ingredients[<?= $ingredient['id'] ?>][quantity]" value="<?= $ingredient['quantity'] ?>">
                    </div>
                    <div class="control">
                        <input class="input" type="text" name="ingredients[<?= $ingredient['id'] ?>][unit]" value="<?= $ingredient['unit'] ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <button class="button is-primary" type="submit">Mettre à jour</button>
        </div>
    </div>
</form>
