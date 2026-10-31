<?php
// meilleur score du joueur (0 s'il n'a encore rien gagné)
$meilleurScore = 0;
foreach ($parties as $partie) {
    if ($partie['score'] !== null && $partie['score'] > $meilleurScore) {
        $meilleurScore = $partie['score'];
    }
}
?>
<main class="conteneur page-liste">
    <p class="sur-titre">MES PARTIES</p>
    <h1>Mon historique</h1>
    <p class="texte-intro">Meilleur score : <span class="mono gras"><?= $meilleurScore ?></span></p>

    <div class="carte tableau-liste">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Durée</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($parties as $partie) : ?>
                    <tr>
                        <td class="mono"><?= date('d/m/Y H:i', strtotime($partie['date_debut'])) ?></td>
                        <td>
                            <?php if ($partie['statut'] === 'gagnee') : ?>
                                <span class="badge badge-succes">Gagnée</span>
                            <?php elseif ($partie['statut'] === 'perdue') : ?>
                                <span class="badge badge-alerte">Perdue</span>
                            <?php else : ?>
                                <span class="badge badge-neutre">Abandonnée</span>
                            <?php endif; ?>
                        </td>
                        <td class="mono"><?= \App\Service\PartieService::formaterDuree($partie['duree']) ?></td>
                        <td class="mono gras"><?= $partie['score'] ?? '-' ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($parties)) : ?>
                    <tr>
                        <td colspan="4">Tu n'as pas encore joué de partie.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <form method="post" action="/partie/nouvelle" class="boutons">
        <input type="hidden" name="csrf_token" value="<?= \App\Security\Csrf::getToken() ?>">
        <button type="submit" class="btn btn-principal">Nouvelle partie</button>
    </form>
</main>
