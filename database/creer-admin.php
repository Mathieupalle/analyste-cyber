<?php
// crée un compte administrateur
// utilisation : docker compose exec app php database/creer-admin.php pseudo email motdepasse

require __DIR__ . '/../vendor/autoload.php';

use App\Repository\UtilisateurRepository;

if (count($argv) !== 4) {
    echo "Utilisation : php database/creer-admin.php pseudo email motdepasse\n";
    exit;
}

$pseudo = $argv[1];
$email = $argv[2];
$motDePasse = $argv[3];

if (strlen($motDePasse) < 8) {
    echo "Le mot de passe doit faire au moins 8 caractères.\n";
    exit;
}

$utilisateurRepository = new UtilisateurRepository();

if ($utilisateurRepository->pseudoExiste($pseudo)) {
    echo "Ce pseudo est déjà utilisé.\n";
    exit;
}

if ($utilisateurRepository->emailExiste($email)) {
    echo "Cet e-mail est déjà utilisé.\n";
    exit;
}

$utilisateurRepository->createAdmin($pseudo, $email, password_hash($motDePasse, PASSWORD_DEFAULT));

echo "Compte admin créé pour " . $pseudo . "\n";
