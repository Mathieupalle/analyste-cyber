<?php

namespace App\Controller;

// pages qui ne contiennent que du texte
class PageController
{
    public function mentionsLegales(): void
    {
        $titre = 'Mentions légales';

        require __DIR__ . '/../../templates/header.php';
        require __DIR__ . '/../../templates/pages/mentions-legales.php';
        require __DIR__ . '/../../templates/footer.php';
    }

    public function confidentialite(): void
    {
        $titre = 'Politique de confidentialité';

        require __DIR__ . '/../../templates/header.php';
        require __DIR__ . '/../../templates/pages/confidentialite.php';
        require __DIR__ . '/../../templates/footer.php';
    }
}
