<!-- menu de l'espace admin (inclus en haut de chaque page admin) -->
<nav class="menu-admin">
    <a href="/admin" class="<?= $pageAdmin === 'tableau' ? 'actif' : '' ?>">Tableau de bord</a>
    <a href="/admin/enigmes" class="<?= $pageAdmin === 'enigmes' ? 'actif' : '' ?>">Enigmes et indices</a>
    <a href="/admin/joueurs" class="<?= $pageAdmin === 'joueurs' ? 'actif' : '' ?>">Joueurs</a>
</nav>
