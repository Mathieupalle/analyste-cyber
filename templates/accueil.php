<main>

    <!-- Bannière -->
    <section class="banniere conteneur">
        <div class="banniere-texte">
            <p class="badge badge-alerte">Alerte incident - Calanques Bio</p>
            <h1>Calanques Bio a été piratée. A toi de trouver comment.</h1>
            <p class="texte-intro">
                Tu es analyste junior au centre de sécurité. Tu as 20 minutes pour retrouver
                comment l'attaquant est entré : un e-mail suspect, des journaux de connexion,
                un mot de passe trop simple.
            </p>
            <div class="boutons">
                <a href="/partie/nouvelle" class="btn btn-principal">
                    <img src="/assets/img/fleche.svg" alt="" width="18" height="18">
                    Commencer l'enquête
                </a>
                <a href="/classement" class="btn btn-secondaire">Voir le classement</a>
            </div>
            <ul class="infos">
                <li><img src="/assets/img/chrono.svg" alt="" width="16" height="16"> 20 minutes</li>
                <li><img src="/assets/img/puzzle.svg" alt="" width="16" height="16"> 4 salles</li>
                <li><img src="/assets/img/ampoule.svg" alt="" width="16" height="16"> Indices disponibles</li>
            </ul>
        </div>

        <div class="carte journal">
            <div class="journal-titre">
                <span>auth.log - srv-calanques-01</span>
                <span class="badge badge-alerte">47 échecs</span>
            </div>
            <div class="journal-lignes">
                <p>03:12:04 <span class="rouge">ECHEC</span> admin 203.0.113.42</p>
                <p>03:12:05 <span class="rouge">ECHEC</span> admin 203.0.113.42</p>
                <p>03:12:07 <span class="rouge">ECHEC</span> s.martin 203.0.113.42</p>
                <p>03:12:09 <span class="rouge">ECHEC</span> s.martin 203.0.113.42</p>
                <p>03:12:11 <span class="vert">SUCCES</span> s.martin 203.0.113.42</p>
                <p class="jaune">03:14:58 ENVOI clients.csv vers serveur externe</p>
            </div>
        </div>
    </section>

    <!-- Les 4 salles -->
    <section class="conteneur section" id="salles">
        <div class="section-titre">
            <div>
                <p class="sur-titre">L'ENQUÊTE</p>
                <h2>4 salles pour comprendre l'attaque</h2>
            </div>
            <p class="petit-texte">Chaque salle se débloque quand la précédente est réussie</p>
        </div>

        <div class="grille-salles">
            <?php foreach ($salles as $salle) : ?>
                <article class="carte salle">
                    <div class="icone-salle">
                        <img src="/assets/img/salle-<?= $salle['numero_ordre'] ?>.svg" alt="" width="22" height="22">
                    </div>
                    <p class="sur-titre">SALLE <?= $salle['numero_ordre'] ?></p>
                    <h3><?= htmlspecialchars($salle['titre']) ?></h3>
                    <p class="description"><?= htmlspecialchars($salle['description']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Classement et score -->
    <section class="conteneur section classement-score">
        <div class="carte top3">
            <div class="carte-titre">
                <h3>Meilleurs analystes</h3>
                <a href="/classement">Voir tout le classement</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pseudo</th>
                        <th>Durée</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>soc_ninja</td>
                        <td class="mono">06:12</td>
                        <td class="mono gras">578</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>m.palle</td>
                        <td class="mono">08:41</td>
                        <td class="mono gras">389</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>blue_team13</td>
                        <td class="mono">09:05</td>
                        <td class="mono gras">345</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="carte calcul-score">
            <h3>Comment est calculé le score ?</h3>
            <ul>
                <li><span>Score de départ</span> <span class="mono">1000</span></li>
                <li><span>Par seconde</span> <span class="mono rouge">-1</span></li>
                <li><span>Par indice</span> <span class="mono rouge">-50</span></li>
                <li><span>Par erreur</span> <span class="mono rouge">-20</span></li>
            </ul>
        </div>
    </section>

</main>
