<main class="conteneur page-admin">
    <?php $pageAdmin = 'joueurs'; require __DIR__ . '/menu.php'; ?>

    <h1>Joueurs</h1>

    <?php if ($message !== '') : ?>
        <div class="message message-succes"><p><?= htmlspecialchars($message) ?></p></div>
    <?php endif; ?>

    <div class="carte tableau-liste">
        <table>
            <thead>
                <tr>
                    <th>Pseudo</th>
                    <th>E-mail</th>
                    <th>Inscription</th>
                    <th>Rôle</th>
                    <th>Parties</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($joueurs as $joueur) : ?>
                    <tr>
                        <td><?= htmlspecialchars($joueur['pseudo']) ?></td>
                        <td><?= htmlspecialchars($joueur['email']) ?></td>
                        <td class="mono"><?= date('d/m/Y', strtotime($joueur['date_inscription'])) ?></td>
                        <td><?= htmlspecialchars($joueur['role']) ?></td>
                        <td class="mono"><?= $joueur['nb_parties'] ?></td>
                        <td>
                            <?php if ($joueur['role'] === 'joueur') : ?>
                                <form method="post" action="/admin/joueurs" onsubmit="return confirm('Supprimer ce compte et toutes ses parties ?');">
                                    <input type="hidden" name="csrf_token" value="<?= \App\Security\Csrf::getToken() ?>">
                                    <input type="hidden" name="id_utilisateur" value="<?= $joueur['id_utilisateur'] ?>">
                                    <button type="submit" class="btn-supprimer">Supprimer</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
