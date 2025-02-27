<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" type="image/png" href="images/cuisine_32x32.png" />
    <link rel="stylesheet" href="/css/main.css">
    <title>Miamm - Les petites recettes futées</title>
</head>
<body>

<header>
    <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item" href="<?= getLink('home');?>">
                    <span class="material-icons navbar-item">home</span>
                    Miamm
                </a>
                <a href="https://www.petitfute.com/" target="_blank" class="navbar-item">
                    Les petites recettes futées
                </a>

                <!-- Bouton Burger pour Mobile -->
                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarMenu">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="navbarMenu" class="navbar-menu">
                <div class="navbar-end">
                    <a class="navbar-item" href="<?= getLink('recettes');?>">Recettes</a>
                    <a class="navbar-item" href="<?= getLink('recette_create');?>">Ajouter</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<main class="container mt-4">
    <?= $content; ?>
</main>

<footer class="footer has-background-primary has-text-light">
    <div class="content has-text-centered">
        <p>&copy; 2024 Miamm - Partage de recettes</p>
    </div>
</footer>

<script>
    // Script pour activer le menu burger sur mobile
    document.addEventListener("DOMContentLoaded", function () {
        const burger = document.querySelector(".navbar-burger");
        const menu = document.querySelector("#navbarMenu");

        burger.addEventListener("click", function () {
            burger.classList.toggle("is-active");
            menu.classList.toggle("is-active");
        });
    });
</script>

</body>
</html>
