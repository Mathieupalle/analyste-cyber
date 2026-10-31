<?php

namespace App\Controller;

use App\Service\PartieService;

class ClassementController
{
    // top 10, visible sans être connecté
    public function index(): void
    {
        $partieService = new PartieService();
        $classement = $partieService->getClassement(10);

        $titre = 'Classement';

        require __DIR__ . '/../../templates/header.php';
        require __DIR__ . '/../../templates/classement.php';
        require __DIR__ . '/../../templates/footer.php';
    }
}
