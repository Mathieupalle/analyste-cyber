// salle 4 : remettre les étapes de l'attaque dans l'ordre
const liste = document.getElementById('liste-etapes');
const champReponse = document.getElementById('reponse');
let carteDeplacee = null;

// met l'ordre des cartes dans le champ caché, séparé par des virgules
function mettreAJourReponse() {
    const valeurs = [];

    liste.querySelectorAll('.etape-attaque').forEach(function (carte) {
        valeurs.push(carte.dataset.valeur);
    });

    champReponse.value = valeurs.join(',');
    document.getElementById('message').hidden = true;
}

liste.querySelectorAll('.etape-attaque').forEach(function (carte) {
    // glisser-déposer (ordinateur)
    carte.addEventListener('dragstart', function () {
        carteDeplacee = carte;
        carte.classList.add('en-deplacement');
    });

    carte.addEventListener('dragend', function () {
        carte.classList.remove('en-deplacement');
        mettreAJourReponse();
    });

    // obligatoire pour pouvoir déposer sur la carte
    carte.addEventListener('dragover', function (event) {
        event.preventDefault();
    });

    carte.addEventListener('drop', function (event) {
        event.preventDefault();

        if (carteDeplacee === carte) {
            return;
        }

        const cartes = Array.from(liste.children);

        // si la carte vient d'au-dessus on la met après, sinon avant
        if (cartes.indexOf(carteDeplacee) < cartes.indexOf(carte)) {
            carte.after(carteDeplacee);
        } else {
            carte.before(carteDeplacee);
        }
    });

    // flèches (pratique sur mobile)
    carte.querySelector('.monter').addEventListener('click', function () {
        if (carte.previousElementSibling) {
            carte.previousElementSibling.before(carte);
            mettreAJourReponse();
        }
    });

    carte.querySelector('.descendre').addEventListener('click', function () {
        if (carte.nextElementSibling) {
            carte.nextElementSibling.after(carte);
            mettreAJourReponse();
        }
    });
});

// ordre de départ
mettreAJourReponse();
