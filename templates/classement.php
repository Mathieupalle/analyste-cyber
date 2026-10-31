<main class="conteneur page-liste">
    <p class="sur-titre">CLASSEMENT</p>
    <h1>Les 10 meilleurs analystes</h1>
    <p class="texte-intro">Seules les enquêtes résolues avant la fin du chrono comptent.</p>

    <div class="carte tableau-liste">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pseudo</th>
                    <th>Date</th>
                    <th>Durée</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classement as $i => $ligne) : ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($ligne['pseudo']) ?></td>
                        <td class="mono"><?= date('d/m/Y', strtotime($ligne['date_debut'])) ?></td>
                        <td class="mono"><?= \App\Service\PartieService::formaterDuree($ligne['duree']) ?></td>
                        <td class="mono gras"><?= $ligne['score'] ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($classement)) : ?>
                    <tr>
                        <td colspan="5">Personne n'a encore résolu l'enquête.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <form method="post" action="/partie/nouvelle" class="boutons">
        <input type="hidden" name="csrf_token" value="<?= \App\Security\Csrf::getToken() ?>">
        <button type="submit" class="btn btn-principal">Jouer une partie</button>
    </form>
</main>
