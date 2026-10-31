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
                // on recharge pour mettre à jour le compteur d'erreurs
                setTimeout(function () {
                    window.location.reload();
                }, 1200);
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
