<?php

namespace App\Controller;

use App\Repository\SalleRepository;
use App\Service\PartieService;

class AccueilController
{
    public function index(): void
    {
        $salleRepository = new SalleRepository();
        $salles = $salleRepository->findAll();

        $partieService = new PartieService();
        $classement = $partieService->getClassement(3);

        $titre = 'Accueil';

        require __DIR__ . '/../../templates/header.php';
        require __DIR__ . '/../../templates/accueil.php';
        require __DIR__ . '/../../templates/footer.php';
    }
}
