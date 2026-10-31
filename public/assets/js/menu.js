// ouvre et ferme le menu sur mobile
const boutonMenu = document.getElementById('bouton-menu');
const menu = document.getElementById('menu');

boutonMenu.addEventListener('click', function () {
    menu.classList.toggle('ouvert');
});
