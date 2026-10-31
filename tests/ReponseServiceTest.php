<?php

use App\Service\ReponseService;
use PHPUnit\Framework\TestCase;

class ReponseServiceTest extends TestCase
{
    public function testTexteExact(): void
    {
        $reponseService = new ReponseService();

        $this->assertTrue($reponseService->estCorrecte('texte', ' 203.0.113.42 ', '203.0.113.42'));
        $this->assertFalse($reponseService->estCorrecte('texte', '198.51.100.7', '203.0.113.42'));
    }

    public function testMotDePasseSensibleALaCasse(): void
    {
        $reponseService = new ReponseService();

        $this->assertFalse($reponseService->estCorrecte('texte', 'calanques2026', 'Calanques2026'));
    }

    public function testSelectionDansNimporteQuelOrdre(): void
    {
        $reponseService = new ReponseService();

        $this->assertTrue($reponseService->estCorrecte('selection', 'urgence, lien, expediteur', 'expediteur,lien,urgence'));
    }

    public function testOrdreRespecte(): void
    {
        $reponseService = new ReponseService();
        $attendue = 'phishing,force_brute,connexion_reussie,exfiltration';

        $this->assertTrue($reponseService->estCorrecte('ordre', 'phishing, force_brute, connexion_reussie, exfiltration', $attendue));
        $this->assertFalse($reponseService->estCorrecte('ordre', 'force_brute,phishing,connexion_reussie,exfiltration', $attendue));
    }
}
