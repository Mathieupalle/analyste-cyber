// graphiques du tableau de bord admin (Chart.js)
// les données sont envoyées par PHP dans l'attribut data-valeurs de chaque canvas

const couleurTexte = '#9AA8BF';
const couleurGrille = '#1E2A42';

// graphique 1 : taux de réussite par salle
const canvasSalles = document.getElementById('graphique-salles');
const salles = JSON.parse(canvasSalles.dataset.valeurs);

new Chart(canvasSalles, {
    type: 'bar',
    data: {
        labels: salles.map(function (ligne) {
            return ligne.salle;
        }),
        datasets: [{
            label: 'Réussite (%)',
            data: salles.map(function (ligne) {
                return ligne.pourcentage;
            }),
            backgroundColor: '#22D3EE'
        }]
    },
    options: {
        indexAxis: 'y',
        scales: {
            x: { min: 0, max: 100, ticks: { color: couleurTexte }, grid: { color: couleurGrille } },
            y: { ticks: { color: couleurTexte }, grid: { display: false } }
        },
        plugins: { legend: { display: false } }
    }
});

// graphique 2 : nombre de parties par jour
const canvasJours = document.getElementById('graphique-jours');
const jours = JSON.parse(canvasJours.dataset.valeurs);

new Chart(canvasJours, {
    type: 'bar',
    data: {
        labels: jours.map(function (ligne) {
            return ligne.jour;
        }),
        datasets: [{
            label: 'Parties',
            data: jours.map(function (ligne) {
                return ligne.nb;
            }),
            backgroundColor: '#155E75'
        }]
    },
    options: {
        scales: {
            x: { ticks: { color: couleurTexte }, grid: { display: false } },
            y: { beginAtZero: true, ticks: { color: couleurTexte, precision: 0 }, grid: { color: couleurGrille } }
        },
        plugins: { legend: { display: false } }
    }
});
