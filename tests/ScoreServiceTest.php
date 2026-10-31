<?php

use App\Service\ScoreService;
use PHPUnit\Framework\TestCase;

class ScoreServiceTest extends TestCase
{
    public function testScoreSansPenalite(): void
    {
        $scoreService = new ScoreService();

        $this->assertSame(1000, $scoreService->calculer(0, 0, 0));
    }

    public function testScoreAvecTempsIndicesEtErreurs(): void
    {
        $scoreService = new ScoreService();

        // 1000 - 521 - 1 x 50 - 2 x 20 = 389
        $this->assertSame(389, $scoreService->calculer(521, 1, 2));
    }

    public function testScoreJamaisNegatif(): void
    {
        $scoreService = new ScoreService();

        $this->assertSame(0, $scoreService->calculer(1199, 5, 10));
    }
}
