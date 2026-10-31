<?php

namespace App\Service;

class ReponseService
{
    // compare la réponse du joueur avec la réponse attendue
    public function estCorrecte(string $type, string $reponseDonnee, string $reponseAttendue): bool
    {
        return $this->normaliser($type, $reponseDonnee) === $this->normaliser($type, $reponseAttendue);
    }

    // met la réponse dans le même format que celle de la base
    public function normaliser(string $type, string $reponse): string
    {
        $reponse = trim($reponse);

        if ($type === 'texte') {
            return $reponse;
        }

        // selection et ordre : valeurs séparées par des virgules
        $valeurs = explode(',', $reponse);
        $valeurs = array_map('trim', $valeurs);

        // pour une sélection, l'ordre des clics ne compte pas
        if ($type === 'selection') {
            sort($valeurs);
        }

        return implode(',', $valeurs);
    }
}
