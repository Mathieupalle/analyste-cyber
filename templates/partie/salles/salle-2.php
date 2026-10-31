<!-- journaux de connexion lus dans MongoDB -->
<div class="carte tableau-journaux">
    <div class="filtres">
        <input type="text" id="filtre-ip" class="mono" placeholder="Filtrer par IP">

        <select id="filtre-statut">
            <option value="">Tous les statuts</option>
            <option value="succes">Succès</option>
            <option value="echec">Echec</option>
        </select>

        <select id="filtre-utilisateur">
            <option value="">Tous les utilisateurs</option>
            <option value="admin">admin</option>
            <option value="a.roux">a.roux</option>
            <option value="c.durand">c.durand</option>
            <option value="j.blanc">j.blanc</option>
            <option value="s.martin">s.martin</option>
        </select>

        <span class="petit-texte"><span id="nb-lignes"><?= count($journaux) ?></span> lignes</span>
    </div>

    <div class="tableau-defilant">
        <table class="mono">
            <thead>
                <tr>
                    <th>Date / heure</th>
                    <th>Utilisateur</th>
                    <th>Adresse IP</th>
                    <th>Statut</th>
                    <th>Navigateur</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($journaux as $journal) : ?>
                    <tr class="ligne-journal"
                        data-ip="<?= htmlspecialchars($journal['ip']) ?>"
                        data-statut="<?= htmlspecialchars($journal['statut']) ?>"
                        data-utilisateur="<?= htmlspecialchars($journal['utilisateur']) ?>">
                        <td><?= date('d/m H:i:s', strtotime($journal['date'])) ?></td>
                        <td><?= htmlspecialchars($journal['utilisateur']) ?></td>
                        <td><?= htmlspecialchars($journal['ip']) ?></td>
                        <?php if ($journal['statut'] === 'succes') : ?>
                            <td class="vert">Succès</td>
                        <?php else : ?>
                            <td class="rouge">Echec</td>
                        <?php endif; ?>
                        <td><?= htmlspecialchars($journal['navigateur']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
