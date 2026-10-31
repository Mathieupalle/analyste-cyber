<?php

namespace App\Controller;

use App\Repository\SalleRepository;

class AccueilController
{
    public function index(): void
    {
        $salleRepository = new SalleRepository();
        $salles = $salleRepository->findAll();

        $titre = 'Accueil';

        require __DIR__ . '/../../templates/header.php';
        require __DIR__ . '/../../templates/accueil.php';
        require __DIR__ . '/../../templates/footer.php';
    }
}
