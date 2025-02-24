recette page liste

<?php foreach($recettes as $recette): ?>
    <h2><?= $recette->getTitre() ?></h2>
    <p><?= $recette->getDescription() ?></p>
    <p><?= $recette->getCategorie() ?></p>
<?php endforeach; ?>