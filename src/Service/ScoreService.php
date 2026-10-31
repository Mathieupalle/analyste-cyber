<?php

namespace App\Service;

class ScoreService
{
    const SCORE_DEPART = 1000;
    const POINTS_PAR_SECONDE = 1;
    const POINTS_PAR_INDICE = 50;
    const POINTS_PAR_ERREUR = 20;

    public function calculer(int $secondes, int $nbIndices, int $nbErreurs): int
    {
        $score = self::SCORE_DEPART
            - $secondes * self::POINTS_PAR_SECONDE
            - $nbIndices * self::POINTS_PAR_INDICE
            - $nbErreurs * self::POINTS_PAR_ERREUR;

        // le score ne peut pas être négatif
        if ($score < 0) {
            $score = 0;
        }

        return $score;
    }
}
