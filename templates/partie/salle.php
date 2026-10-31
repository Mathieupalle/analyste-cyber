<main>

    <!-- barre de jeu : progression, compteurs et chrono -->
    <div class="barre-jeu">
        <div class="conteneur barre-jeu-contenu">
            <ol class="progression">
                <?php for ($i = 1; $i <= 4; $i++) : ?>
                    <?php if ($i < $numeroSalle) : ?>
                        <li class="etape etape-finie"><?= $i ?></li>
                    <?php elseif ($i === $numeroSalle) : ?>
                        <li class="etape etape-en-cours"><?= $i ?></li>
                    <?php else : ?>
                        <li class="etape"><?= $i ?></li>
                    <?php endif; ?>
                <?php endfor; ?>
            </ol>

            <div class="compteurs">
                <span><span id="nb-indices"><?= $compteurs['indices'] ?></span> indice(s)</span>
                <span><span id="nb-erreurs"><?= $compteurs['erreurs'] ?></span> erreur(s)</span>
                <span class="chrono mono" id="chrono" data-secondes="<?= $secondesRestantes ?>">--:--</span>
            </div>
        </div>
    </div>

    <section class="conteneur salle-jeu">
        <div class="salle-texte">
            <p class="sur-titre">SALLE <?= $numeroSalle ?> SUR 4</p>
            <h1><?= htmlspecialchars($enigme['titre']) ?></h1>
            <p class="texte-intro"><?= htmlspecialchars($enigme['description']) ?></p>
        </div>

        <!-- contenu propre à chaque salle -->
        <div class="salle-contenu">
            <?php require __DIR__ . '/salles/salle-' . $numeroSalle . '.php'; ?>
        </div>

        <!-- formulaire de réponse (envoyé en JavaScript avec fetch) -->
        <form class="carte formulaire-reponse" id="formulaire-reponse">
            <input type="hidden" name="csrf_token" value="<?= \App\Security\Csrf::getToken() ?>">

            <?php if ($enigme['type_reponse'] !== 'texte') : ?>
                <!-- la réponse est remplie par le JavaScript de la salle -->
                <p class="gras"><?= htmlspecialchars($enigme['intitule']) ?></p>
                <input type="hidden" id="reponse" name="reponse">
            <?php else : ?>
                <label for="reponse"><?= htmlspecialchars($enigme['intitule']) ?></label>
                <input type="text" id="reponse" name="reponse" class="mono" autocomplete="off" required>
            <?php endif; ?>

            <p class="message" id="message" hidden></p>

            <button type="submit" class="btn btn-principal">Valider ma réponse</button>
        </form>

        <!-- indices : mentor IA ou indice de secours -->
        <div class="zone-indices">
            <div id="liste-indices"></div>
            <button type="button" class="btn btn-secondaire" id="bouton-indice">
                <img src="/assets/img/ampoule.svg" alt="" width="18" height="18">
                Demander un indice (-50 points)
            </button>
        </div>
    </section>

</main>

<script src="/assets/js/salle.js"></script>
<?php if ($numeroSalle === 1) : ?>
    <script src="/assets/js/salle-1.js"></script>
<?php endif; ?>
<?php if ($numeroSalle === 2) : ?>
    <script src="/assets/js/salle-2.js"></script>
<?php endif; ?>
<?php if ($numeroSalle === 4) : ?>
    <script src="/assets/js/salle-4.js"></script>
<?php endif; ?>
