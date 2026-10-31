// salle 1 : le joueur clique sur les éléments suspects de l'e-mail
const zones = document.querySelectorAll('.zone');
const champReponse = document.getElementById('reponse');
const compteurZones = document.getElementById('compteur-zones');

zones.forEach(function (zone) {
    zone.addEventListener('click', function () {
        const nbChoisis = document.querySelectorAll('.zone.choisi').length;

        // on ne peut pas choisir plus de 3 éléments
        if (!zone.classList.contains('choisi') && nbChoisis >= 3) {
            return;
        }

        zone.classList.toggle('choisi');
        mettreAJourReponse();
    });
});

// met les valeurs choisies dans le champ caché, séparées par des virgules
function mettreAJourReponse() {
    const valeurs = [];

    document.querySelectorAll('.zone.choisi').forEach(function (zone) {
        valeurs.push(zone.dataset.valeur);
    });

    champReponse.value = valeurs.join(',');
    compteurZones.textContent = valeurs.length;

    // on cache l'ancien message d'erreur
    document.getElementById('message').hidden = true;
}
