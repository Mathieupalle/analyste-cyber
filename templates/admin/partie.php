<?php
// textes affichés pour chaque type d'événement enregistré dans MongoDB
$libelles = [
    'partie_demarree' => 'Début de la partie',
    'reponse' => 'Réponse envoyée',
    'indice' => 'Indice demandé',
    'salle_validee' => 'Salle réussie',
    'partie_gagnee' => 'Partie gagnée',
    'temps_ecoule' => 'Temps écoulé',
];
?>
<main class="conteneur page-admin">
    <?php $pageAdmin = 'tableau'; require __DIR__ . '/menu.php'; ?>

    <h1>Déroulé de la partie n°<?= $partie['id_partie'] ?></h1>
    <p class="texte-intro">
        Joueur : <?= htmlspecialchars($partie['pseudo']) ?> -
        statut : <?= htmlspecialchars($partie['statut']) ?> -
        score : <?= $partie['score'] ?? '-' ?>
    </p>

    <div class="carte tableau-liste">
        <table>
            <thead>
                <tr>
                    <th>Heure</th>
                    <th>Salle</th>
                    <th>Evénement</th>
                    <th>Détail</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($partie['evenements'] as $evenement) : ?>
                    <tr>
                        <td class="mono"><?= date('H:i:s', strtotime($evenement['date'])) ?></td>
                        <td><?= $evenement['salle'] ?></td>
                        <td><?= $libelles[$evenement['type']] ?? htmlspecialchars($evenement['type']) ?></td>
                        <td>
                            <?php if ($evenement['type'] === 'reponse') : ?>
                                <span class="mono"><?= htmlspecialchars($evenement['details']['reponse']) ?></span>
                                <?php if ($evenement['details']['correcte']) : ?>
                                    <span class="vert">juste</span>
                                <?php else : ?>
                                    <span class="rouge">fausse</span>
                                <?php endif; ?>
                            <?php elseif ($evenement['type'] === 'indice') : ?>
                                indice n°<?= $evenement['details']['numero'] ?> (<?= htmlspecialchars($evenement['details']['source']) ?>)
                            <?php elseif ($evenement['type'] === 'partie_gagnee') : ?>
                                score : <?= $evenement['details']['score'] ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($partie['evenements'])) : ?>
                    <tr>
                        <td colspan="4">Aucun événement enregistré pour cette partie.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <p><a href="/admin">Retour au tableau de bord</a></p>
</main>
