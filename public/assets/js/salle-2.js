// salle 2 : filtre du tableau des journaux de connexion
const filtreIp = document.getElementById('filtre-ip');
const filtreStatut = document.getElementById('filtre-statut');
const filtreUtilisateur = document.getElementById('filtre-utilisateur');
const nbLignes = document.getElementById('nb-lignes');
const lignes = document.querySelectorAll('.ligne-journal');

function filtrer() {
    const ip = filtreIp.value.trim();
    const statut = filtreStatut.value;
    const utilisateur = filtreUtilisateur.value;
    let compteur = 0;

    lignes.forEach(function (ligne) {
        let visible = true;

        if (ip !== '' && !ligne.dataset.ip.includes(ip)) {
            visible = false;
        }

        if (statut !== '' && ligne.dataset.statut !== statut) {
            visible = false;
        }

        if (utilisateur !== '' && ligne.dataset.utilisateur !== utilisateur) {
            visible = false;
        }

        ligne.hidden = !visible;

        if (visible) {
            compteur++;
        }
    });

    nbLignes.textContent = compteur;
}

filtreIp.addEventListener('input', filtrer);
filtreStatut.addEventListener('change', filtrer);
filtreUtilisateur.addEventListener('change', filtrer);
