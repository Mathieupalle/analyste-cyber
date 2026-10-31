<main class="conteneur page-admin">
    <?php $pageAdmin = 'enigmes'; require __DIR__ . '/menu.php'; ?>

    <h1>Enigmes et indices</h1>

    <?php if ($succes) : ?>
        <div class="message message-succes"><p>L'énigme a bien été modifiée.</p></div>
    <?php endif; ?>

    <?php if (!empty($erreurs)) : ?>
        <div class="message message-erreur">
            <?php foreach ($erreurs as $erreur) : ?>
                <p><?= htmlspecialchars($erreur) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php foreach ($enigmes as $enigme) : ?>
        <form method="post" action="/admin/enigmes" class="carte formulaire-admin">
            <input type="hidden" name="csrf_token" value="<?= \App\Security\Csrf::getToken() ?>">
            <input type="hidden" name="id_enigme" value="<?= $enigme['id_enigme'] ?>">

            <h3>Salle <?= $enigme['numero_ordre'] ?> - <?= htmlspecialchars($enigme['titre']) ?></h3>
            <p class="petit-texte">Type de réponse : <?= htmlspecialchars($enigme['type_reponse']) ?></p>

            <label for="intitule-<?= $enigme['id_enigme'] ?>">Question</label>
            <input type="text" id="intitule-<?= $enigme['id_enigme'] ?>" name="intitule" value="<?= htmlspecialchars($enigme['intitule']) ?>" required maxlength="255">

            <label for="reponse-<?= $enigme['id_enigme'] ?>">Réponse attendue</label>
            <input type="text" id="reponse-<?= $enigme['id_enigme'] ?>" name="reponse_attendue" class="mono" value="<?= htmlspecialchars($enigme['reponse_attendue']) ?>" required maxlength="255">

            <label for="indice1-<?= $enigme['id_enigme'] ?>">Indice de secours 1</label>
            <input type="text" id="indice1-<?= $enigme['id_enigme'] ?>" name="indice1" value="<?= htmlspecialchars($enigme['indice1']) ?>" required maxlength="255">

            <label for="indice2-<?= $enigme['id_enigme'] ?>">Indice de secours 2</label>
            <input type="text" id="indice2-<?= $enigme['id_enigme'] ?>" name="indice2" value="<?= htmlspecialchars($enigme['indice2']) ?>" required maxlength="255">

            <button type="submit" class="btn btn-principal">Enregistrer</button>
        </form>
    <?php endforeach; ?>
</main>
