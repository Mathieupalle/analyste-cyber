<main class="conteneur page-formulaire">
    <div class="carte formulaire">
        <h1>Créer un compte</h1>
        <p class="petit-texte">Inscris-toi pour lancer ta première enquête.</p>

        <?php if (!empty($erreurs)) : ?>
            <div class="message message-erreur">
                <?php foreach ($erreurs as $erreur) : ?>
                    <p><?= htmlspecialchars($erreur) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/inscription">
            <input type="hidden" name="csrf_token" value="<?= \App\Security\Csrf::getToken() ?>">

            <label for="pseudo">Pseudo</label>
            <input type="text" id="pseudo" name="pseudo" value="<?= htmlspecialchars($pseudo) ?>" required minlength="3" maxlength="30">

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required minlength="8">
            <p class="aide">8 caractères minimum</p>

            <label for="confirmation">Confirmer le mot de passe</label>
            <input type="password" id="confirmation" name="confirmation" required minlength="8">

            <label class="case">
                <input type="checkbox" name="accepte" required>
                J'accepte la <a href="/confidentialite">politique de confidentialité</a>
            </label>

            <button type="submit" class="btn btn-principal">Créer mon compte</button>
        </form>

        <p class="lien-bas">Déjà un compte ? <a href="/connexion">Se connecter</a></p>
    </div>
</main>
