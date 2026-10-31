<?php

namespace App\Service;

use App\Repository\DemandeIndiceRepository;
use App\Repository\EnigmeRepository;
use App\Repository\JournalRepository;
use App\Repository\PartieRepository;
use App\Repository\TentativeRepository;

class PartieService
{
    const NB_SALLES = 4;
    const DUREE_MAX = 1200; // 20 minutes en secondes

    private PartieRepository $partieRepository;
    private EnigmeRepository $enigmeRepository;
    private TentativeRepository $tentativeRepository;
    private DemandeIndiceRepository $demandeIndiceRepository;

    public function __construct()
    {
        $this->partieRepository = new PartieRepository();
        $this->enigmeRepository = new EnigmeRepository();
        $this->tentativeRepository = new TentativeRepository();
        $this->demandeIndiceRepository = new DemandeIndiceRepository();
    }

    // une seule partie en cours : on abandonne l'ancienne avant d'en créer une
    public function demarrer(int $idUtilisateur): void
    {
        $maintenant = date('Y-m-d H:i:s');

        $this->partieRepository->abandonnerEnCours($idUtilisateur, $maintenant);
        $this->partieRepository->create($idUtilisateur, $maintenant);
    }

    // renvoie la partie en cours, ou null si elle n'existe pas ou si le temps est écoulé
    public function getPartieEnCours(int $idUtilisateur): ?array
    {
        $partie = $this->partieRepository->findEnCours($idUtilisateur);

        if ($partie === null) {
            return null;
        }

        $partie['secondes'] = time() - strtotime($partie['date_debut']);

        if ($partie['secondes'] >= self::DUREE_MAX) {
            $this->partieRepository->terminer($partie['id_partie'], 'perdue', null, date('Y-m-d H:i:s'));
            return null;
        }

        return $partie;
    }

    // la salle où se trouve le joueur = nombre de salles réussies + 1
    public function getSalleCourante(int $idPartie): int
    {
        return $this->tentativeRepository->compterReussites($idPartie) + 1;
    }

    public function getEnigme(int $numeroSalle): ?array
    {
        return $this->enigmeRepository->findBySalle($numeroSalle);
    }

    // journaux de connexion de la salle 2 (MongoDB)
    public function getJournaux(): array
    {
        $journalRepository = new JournalRepository();

        return $journalRepository->findAll();
    }

    public function getCompteurs(int $idPartie): array
    {
        return [
            'erreurs' => $this->tentativeRepository->compterErreurs($idPartie),
            'indices' => $this->demandeIndiceRepository->compter($idPartie),
        ];
    }

    // vérifie la réponse du joueur pour la salle où il se trouve
    // renvoie : 'expire', 'faux', 'juste' ou 'termine'
    public function repondre(int $idUtilisateur, string $reponse): string
    {
        $partie = $this->getPartieEnCours($idUtilisateur);

        if ($partie === null) {
            return 'expire';
        }

        $numeroSalle = $this->getSalleCourante($partie['id_partie']);
        $enigme = $this->enigmeRepository->findBySalle($numeroSalle);
        $reponseAttendue = $this->enigmeRepository->findReponse($enigme['id_enigme']);

        $reponseService = new ReponseService();
        $estCorrecte = $reponseService->estCorrecte($enigme['type_reponse'], $reponse, $reponseAttendue);

        $this->tentativeRepository->create($partie['id_partie'], $enigme['id_enigme'], $reponse, $estCorrecte, date('Y-m-d H:i:s'));

        if (!$estCorrecte) {
            return 'faux';
        }

        // dernière salle réussie : fin de la partie
        if ($numeroSalle === self::NB_SALLES) {
            $this->terminerGagnee($partie);
            return 'termine';
        }

        return 'juste';
    }

    private function terminerGagnee(array $partie): void
    {
        $compteurs = $this->getCompteurs($partie['id_partie']);

        $scoreService = new ScoreService();
        $score = $scoreService->calculer($partie['secondes'], $compteurs['indices'], $compteurs['erreurs']);

        $this->partieRepository->terminer($partie['id_partie'], 'gagnee', $score, date('Y-m-d H:i:s'));
    }

    // les infos de la dernière partie pour la page de fin
    public function getResultat(int $idUtilisateur): ?array
    {
        $partie = $this->partieRepository->findDerniere($idUtilisateur);

        if ($partie === null) {
            return null;
        }

        $compteurs = $this->getCompteurs($partie['id_partie']);

        $partie['duree'] = strtotime($partie['date_fin']) - strtotime($partie['date_debut']);
        $partie['erreurs'] = $compteurs['erreurs'];
        $partie['indices'] = $compteurs['indices'];

        return $partie;
    }
}
