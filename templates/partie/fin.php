<main class="conteneur page-fin">

    <?php if ($partie['statut'] === 'gagnee') : ?>
        <p class="badge badge-succes">Enquête résolue</p>
        <h1>Bravo, tu as retrouvé l'attaque !</h1>
        <p class="texte-intro">
            Un e-mail de phishing a visé s.martin. Son mot de passe était trop simple et a été trouvé
            depuis 203.0.113.42, puis le fichier clients a été volé.
        </p>

        <div class="score">
            <div class="carte score-final">
                <p class="petit-texte">TON SCORE</p>
                <p class="score-chiffre mono"><?= $partie['score'] ?></p>
            </div>

            <div class="carte calcul-score">
                <h3>Détail du score</h3>
                <ul>
                    <li><span>Score de départ</span> <span class="mono">1000</span></li>
                    <li><span>Temps : <?= \App\Service\PartieService::formaterDuree($partie['duree']) ?> (<?= $partie['duree'] ?> s)</span> <span class="mono rouge">-<?= $partie['duree'] ?></span></li>
                    <li><span>Indices : <?= $partie['indices'] ?></span> <span class="mono rouge">-<?= $partie['indices'] * \App\Service\ScoreService::POINTS_PAR_INDICE ?></span></li>
                    <li><span>Erreurs : <?= $partie['erreurs'] ?></span> <span class="mono rouge">-<?= $partie['erreurs'] * \App\Service\ScoreService::POINTS_PAR_ERREUR ?></span></li>
                    <li class="gras"><span>Score final</span> <span class="mono"><?= $partie['score'] ?></span></li>
                </ul>
            </div>
        </div>
    <?php else : ?>
        <p class="badge badge-alerte">Temps écoulé</p>
        <h1>L'attaquant t'a échappé...</h1>
        <p class="texte-intro">Les 20 minutes sont passées. Cette partie ne compte pas dans le classement.</p>
    <?php endif; ?>

    <form method="post" action="/partie/nouvelle" class="boutons">
        <input type="hidden" name="csrf_token" value="<?= \App\Security\Csrf::getToken() ?>">
        <button type="submit" class="btn btn-principal">Rejouer</button>
        <a href="/classement" class="btn btn-secondaire">Voir le classement</a>
        <a href="/mes-parties" class="btn btn-secondaire">Mes parties</a>
    </form>

</main>
