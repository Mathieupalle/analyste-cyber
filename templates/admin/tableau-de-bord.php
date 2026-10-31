<main class="conteneur page-admin">
    <?php $pageAdmin = 'tableau'; require __DIR__ . '/menu.php'; ?>

    <h1>Tableau de bord</h1>

    <!-- chiffres clés -->
    <div class="chiffres-cles">
        <div class="carte chiffre">
            <p class="petit-texte">Parties jouées</p>
            <p class="chiffre-valeur"><?= $chiffres['nb_parties'] ?></p>
        </div>
        <div class="carte chiffre">
            <p class="petit-texte">Taux de réussite</p>
            <p class="chiffre-valeur"><?= $chiffres['taux_reussite'] ?> %</p>
            <p class="petit-texte"><?= $chiffres['nb_gagnees'] ?> enquête(s) résolue(s)</p>
        </div>
        <div class="carte chiffre">
            <p class="petit-texte">Durée moyenne (parties gagnées)</p>
            <p class="chiffre-valeur mono"><?= \App\Service\PartieService::formaterDuree($chiffres['duree_moyenne']) ?></p>
        </div>
        <div class="carte chiffre">
            <p class="petit-texte">Indices par partie</p>
            <p class="chiffre-valeur"><?= number_format($chiffres['indices_par_partie'], 1, ',', '') ?></p>
            <p class="petit-texte"><?= $chiffres['indices_ia'] ?> sur <?= $chiffres['indices_total'] ?> donnés par le mentor IA</p>
        </div>
    </div>

    <!-- graphiques (Chart.js) : les données sont dans l'attribut data-valeurs -->
    <div class="graphiques">
        <div class="carte graphique">
            <h3>Taux de réussite par salle</h3>
            <canvas id="graphique-salles" data-valeurs="<?= htmlspecialchars(json_encode($reussiteParSalle)) ?>"></canvas>
        </div>
        <div class="carte graphique">
            <h3>Parties par jour</h3>
            <canvas id="graphique-jours" data-valeurs="<?= htmlspecialchars(json_encode($partiesParJour)) ?>"></canvas>
        </div>
    </div>

    <!-- dernières parties -->
    <div class="carte tableau-liste">
        <h3>Dernières parties</h3>
        <table>
            <thead>
                <tr>
                    <th>Joueur</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Score</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dernieresParties as $partie) : ?>
                    <tr>
                        <td><?= htmlspecialchars($partie['pseudo']) ?></td>
                        <td class="mono"><?= date('d/m/Y H:i', strtotime($partie['date_debut'])) ?></td>
                        <td><?= htmlspecialchars($partie['statut']) ?></td>
                        <td class="mono"><?= $partie['score'] ?? '-' ?></td>
                        <td><a href="/admin/partie?id=<?= $partie['id_partie'] ?>">Voir le déroulé</a></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($dernieresParties)) : ?>
                    <tr>
                        <td colspan="5">Aucune partie pour l'instant.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="/assets/js/admin.js"></script>
