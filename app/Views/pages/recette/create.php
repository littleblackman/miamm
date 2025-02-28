<section class="section">
    <div class="container">
        <h1 class="title has-text-centered">➕ Ajouter une Recette</h1>

        <div class="box">
            <form action="<?= getLink('recette_store') ;?>" method="POST">
                <div class="field">
                    <label class="label">Lien de la recette</label>
                    <div class="control">
                        <input class="input" type="url" name="recette_url" placeholder="https://example.com/recette" required>
                    </div>

                    <p>
                        Ajoutez le lien de la recette pour la partager avec la communauté.<br/>
                        Pour la phase de test, nous vous recommandons le site <a href="https//www.marmiton.org" target="_blank">Marmiton</a>
                    </p>
                </div>

                <div class="field">
                    <div class="control">
                        <button type="submit" class="button is-primary">Ajouter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
