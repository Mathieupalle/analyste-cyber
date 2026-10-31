<main class="conteneur page-formulaire">
    <div class="carte formulaire">
        <h1>Se connecter</h1>
        <p class="petit-texte">Reprends ton enquête là où tu l'as laissée.</p>

        <?php if (isset($_GET['inscription'])) : ?>
            <div class="message message-succes">
                <p>Ton compte a bien été créé, tu peux te connecter.</p>
            </div>
        <?php endif; ?>

        <?php if ($erreur !== '') : ?>
            <div class="message message-erreur">
                <p><?= htmlspecialchars($erreur) ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="/connexion">
            <input type="hidden" name="csrf_token" value="<?= \App\Security\Csrf::getToken() ?>">

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required>

            <button type="submit" class="btn btn-principal">Se connecter</button>
        </form>

        <p class="lien-bas">Pas encore de compte ? <a href="/inscription">Créer un compte</a></p>
    </div>
</main>
