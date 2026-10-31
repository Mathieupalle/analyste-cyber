<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titre ?> - Analyste cyber</title>
    <meta name="description" content="Escape game sur la cybersécurité : retrouve comment l'attaquant est entré chez Calanques Bio.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<header class="entete">
    <div class="conteneur entete-contenu">
        <a href="/" class="logo">
            <img src="/assets/img/logo.svg" alt="" width="26" height="26">
            Analyste cyber
        </a>

        <button class="bouton-menu" id="bouton-menu">
            <img src="/assets/img/menu.svg" alt="Menu" width="24" height="24">
        </button>

        <nav class="menu" id="menu">
            <a href="/#salles">Les salles</a>
            <a href="/classement">Classement</a>
            <a href="/connexion">Se connecter</a>
            <a href="/inscription" class="btn btn-principal">Créer un compte</a>
        </nav>
    </div>
</header>
