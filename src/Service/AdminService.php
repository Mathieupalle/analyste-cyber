<?php

namespace App\Service;

use App\Repository\DemandeIndiceRepository;
use App\Repository\EnigmeRepository;
use App\Repository\EvenementRepository;
use App\Repository\IndiceRepository;
use App\Repository\PartieRepository;
use App\Repository\SalleRepository;
use App\Repository\UtilisateurRepository;

class AdminService
{
    private PartieRepository $partieRepository;
    private EnigmeRepository $enigmeRepository;
    private IndiceRepository $indiceRepository;
    private UtilisateurRepository $utilisateurRepository;

    public function __construct()
    {
        $this->partieRepository = new PartieRepository();
        $this->enigmeRepository = new EnigmeRepository();
        $this->indiceRepository = new IndiceRepository();
        $this->utilisateurRepository = new UtilisateurRepository();
    }

    // les 4 chiffres clés du tableau de bord
    public function getChiffresCles(): array
    {
        $nbParties = $this->partieRepository->compterTerminees();
        $gagnees = $this->partieRepository->findGagnees();

        // durée moyenne des parties gagnées
        $dureeTotale = 0;
        foreach ($gagnees as $partie) {
            $dureeTotale += strtotime($partie['date_fin']) - strtotime($partie['date_debut']);
        }

        $dureeMoyenne = 0;
        $tauxReussite = 0;
        if (count($gagnees) > 0) {
            $dureeMoyenne = intdiv($dureeTotale, count($gagnees));
        }
        if ($nbParties > 0) {
            $tauxReussite = round(count($gagnees) * 100 / $nbParties);
        }

        $demandeIndiceRepository = new DemandeIndiceRepository();
        $indices = $demandeIndiceRepository->compterTout();

        $indicesParPartie = 0;
        if ($nbParties > 0) {
            $indicesParPartie = round($indices['total'] / $nbParties, 1);
        }

        return [
            'nb_parties' => $nbParties,
            'nb_gagnees' => count($gagnees),
            'taux_reussite' => $tauxReussite,
            'duree_moyenne' => $dureeMoyenne,
            'indices_par_partie' => $indicesParPartie,
            'indices_total' => (int) $indices['total'],
            'indices_ia' => (int) $indices['ia'],
        ];
    }

    // pour le graphique : pourcentage de parties qui ont réussi chaque salle
    public function getReussiteParSalle(): array
    {
        $salleRepository = new SalleRepository();
        $salles = $salleRepository->findReussites();
        $nbParties = $this->partieRepository->compterTerminees();

        $resultat = [];
        foreach ($salles as $salle) {
            $pourcentage = 0;
            if ($nbParties > 0) {
                $pourcentage = round($salle['nb_reussites'] * 100 / $nbParties);
            }

            $resultat[] = [
                'salle' => 'Salle ' . $salle['numero_ordre'] . ' - ' . $salle['titre'],
                'pourcentage' => $pourcentage,
            ];
        }

        return $resultat;
    }

    public function getPartiesParJour(): array
    {
        $jours = $this->partieRepository->compterParJour();

        // date au format français pour le graphique (2026-10-30 -> 30/10)
        foreach ($jours as $i => $jour) {
            $jours[$i]['jour'] = date('d/m', strtotime($jour['jour']));
        }

        return $jours;
    }

    public function getDernieresParties(): array
    {
        return $this->partieRepository->findDernieres(10);
    }

    // les énigmes avec leurs 2 indices de secours
    public function getEnigmes(): array
    {
        $enigmes = $this->enigmeRepository->findAll();

        foreach ($enigmes as $i => $enigme) {
            $enigmes[$i]['indice1'] = $this->indiceRepository->findTexte($enigme['id_enigme'], 1);
            $enigmes[$i]['indice2'] = $this->indiceRepository->findTexte($enigme['id_enigme'], 2);
        }

        return $enigmes;
    }

    // renvoie la liste des erreurs (vide si tout est bon)
    public function modifierEnigme(int $idEnigme, string $intitule, string $reponse, string $indice1, string $indice2): array
    {
        $erreurs = [];

        if ($intitule === '' || $reponse === '' || $indice1 === '' || $indice2 === '') {
            $erreurs[] = 'Tous les champs sont obligatoires.';
        }

        if (mb_strlen($intitule) > 255 || mb_strlen($reponse) > 255 || mb_strlen($indice1) > 255 || mb_strlen($indice2) > 255) {
            $erreurs[] = 'Chaque champ doit faire 255 caractères maximum.';
        }

        if (empty($erreurs)) {
            $this->enigmeRepository->update($idEnigme, $intitule, $reponse);
            $this->indiceRepository->update($idEnigme, 1, $indice1);
            $this->indiceRepository->update($idEnigme, 2, $indice2);
        }

        return $erreurs;
    }

    public function getJoueurs(): array
    {
        return $this->utilisateurRepository->findAll();
    }

    // supprime un joueur et tout son historique (droit à l'effacement, RGPD)
    public function supprimerJoueur(int $idUtilisateur): void
    {
        // on récupère ses parties pour effacer aussi leur historique dans MongoDB
        $idsParties = $this->partieRepository->findIdsByUtilisateur($idUtilisateur);

        $evenementRepository = new EvenementRepository();
        $evenementRepository->supprimerParParties($idsParties);

        // dans MySQL, les parties, réponses et indices partent avec le compte (ON DELETE CASCADE)
        $this->utilisateurRepository->delete($idUtilisateur);
    }

    // une partie et ses événements enregistrés dans MongoDB
    public function getDeroule(int $idPartie): ?array
    {
        $partie = $this->partieRepository->find($idPartie);

        if ($partie === null) {
            return null;
        }

        $evenementRepository = new EvenementRepository();
        $partie['evenements'] = $evenementRepository->findByPartie($idPartie);

        return $partie;
    }
}
