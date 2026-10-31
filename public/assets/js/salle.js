// chrono : on part des secondes restantes envoyées par le serveur
const chrono = document.getElementById('chrono');
let secondesRestantes = parseInt(chrono.dataset.secondes);

function afficherChrono() {
    const minutes = Math.floor(secondesRestantes / 60);
    const secondes = secondesRestantes % 60;

    chrono.textContent = String(minutes).padStart(2, '0') + ':' + String(secondes).padStart(2, '0');
}

afficherChrono();

setInterval(function () {
    secondesRestantes--;

    if (secondesRestantes <= 0) {
        // temps écoulé : le serveur termine la partie
        window.location.href = '/partie';
        return;
    }

    afficherChrono();
}, 1000);


// envoi de la réponse sans recharger la page
const formulaire = document.getElementById('formulaire-reponse');
const message = document.getElementById('message');

formulaire.addEventListener('submit', function (event) {
    event.preventDefault();

    // réponse vide (par exemple aucun élément cliqué dans la salle 1)
    if (document.getElementById('reponse').value === '') {
        message.hidden = false;
        message.className = 'message message-erreur';
        message.textContent = 'Choisis une réponse avant de valider.';
        return;
    }

    const donnees = new FormData(formulaire);

    fetch('/partie/repondre', {
        method: 'POST',
        body: donnees
    })
        .then(function (reponse) {
            return reponse.json();
        })
        .then(function (data) {
            message.hidden = false;

            if (data.resultat === 'faux') {
                message.className = 'message message-erreur';
                message.textContent = 'Ce n\'est pas la bonne réponse.';

                // on ajoute 1 au compteur d'erreurs
                const nbErreurs = document.getElementById('nb-erreurs');
                nbErreurs.textContent = parseInt(nbErreurs.textContent) + 1;
            } else if (data.resultat === 'juste') {
                message.className = 'message message-succes';
                message.textContent = 'Bonne réponse ! Salle suivante...';
                setTimeout(function () {
                    window.location.reload();
                }, 1200);
            } else {
                // termine ou expire
                window.location.href = '/partie/fin';
            }
        })
        .catch(function () {
            message.hidden = false;
            message.className = 'message message-erreur';
            message.textContent = 'Erreur de connexion, réessaie.';
        });
});


// bouton "Demander un indice"
const boutonIndice = document.getElementById('bouton-indice');
const listeIndices = document.getElementById('liste-indices');

boutonIndice.addEventListener('click', function () {
    if (!confirm('Un indice coûte 50 points. Tu veux continuer ?')) {
        return;
    }

    const donnees = new FormData();
    donnees.append('csrf_token', formulaire.querySelector('[name="csrf_token"]').value);

    boutonIndice.disabled = true;

    fetch('/partie/indice', {
        method: 'POST',
        body: donnees
    })
        .then(function (reponse) {
            return reponse.json();
        })
        .then(function (data) {
            boutonIndice.disabled = false;

            if (data.resultat === 'expire') {
                window.location.href = '/partie/fin';
                return;
            }

            if (data.resultat === 'max') {
                boutonIndice.disabled = true;
                boutonIndice.textContent = 'Plus d\'indice pour cette salle';
                return;
            }

            // on affiche l'indice (textContent : pas de HTML injecté)
            const bloc = document.createElement('p');
            bloc.className = 'message message-indice';

            if (data.source === 'ia') {
                bloc.textContent = 'Mentor IA : ' + data.texte;
            } else {
                bloc.textContent = 'Indice : ' + data.texte;
            }

            listeIndices.appendChild(bloc);

            const nbIndices = document.getElementById('nb-indices');
            nbIndices.textContent = parseInt(nbIndices.textContent) + 1;
        })
        .catch(function () {
            boutonIndice.disabled = false;
            alert('Erreur de connexion, réessaie.');
        });
});
