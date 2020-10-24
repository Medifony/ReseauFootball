<?php

namespace App\Controller;

use DateTime;
use FootballData;
use App\Entity\Club;
use App\Entity\Joueur;
use App\Entity\Rencontre;
use App\Entity\Classement;
use App\Entity\Championnat;
use App\Repository\ClubRepository;
use App\Repository\PaysRepository;
use App\Repository\RencontreRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ChampionnatRepository;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminReseauController extends AbstractController
{
    public function index()
    {
        

        return $this->render('admin/reseau/index.html.twig', [
        ]);
    }

    public function apiLiga(EntityManagerInterface $manager, PaysRepository $repoPays, ChampionnatRepository $repoChamp)
    {   
        $champExiste = $repoChamp->findOneByApiId(2014);

        if($champExiste == null)
        {
        // Création du championnat
        $championnat = new Championnat();
        $pays = $repoPays->findOneByNomFr('Espagne');

        $championnat->setNom('La Liga');
        $championnat->setLogo('/images/logo/ligaLogo.png');
        $championnat->setNavbar('Liga');
        $championnat->setPayss($pays);
        $championnat->setApiId(2014);
        
        $manager->persist($championnat);

        //Creation des équipes
        $api = new FootballData();

        $equipes = $api->findTeamsCompetitionById(2014);

        try{
            foreach($equipes->teams as $equipe){
                $club = new Club();
                
                $club->setNom($equipe->name);
                $club->setLogo($equipe->crestUrl);
                $club->setNavbar($equipe->shortName);
                $club->setChampionnats($championnat);
                $club->setApiId($equipe->id);

                $manager->persist($club);
            }

            $manager->flush();

            $this->addFlash(
                'success',
                "Le championnat <strong>{$championnat->getNom()}</strong> a bien été enregistré !"
            );
        }catch(UniqueConstraintViolationException $ex){
            $this->addFlash(
                'danger',
                "Un ou des clubs du championnat ont déjà été importés !"
        );
        }
        return $this->redirectToRoute('admin_api_index');
        }
        else
        {
            $this->addFlash(
                'danger',
                "Un championnat possède déjà cet ID !"
            );

            return $this->redirectToRoute('admin_api_index');
        }
    }

    public function apiPremierl(EntityManagerInterface $manager, PaysRepository $repoPays, ChampionnatRepository $repoChamp)
    {
        $champExiste = $repoChamp->findOneByApiId(2021);

        if($champExiste == null)
        {
        // Création du championnat
        $championnat = new Championnat();
        $pays = $repoPays->findOneByNomFr('Royaume-Uni');

        $championnat->setNom('Premier League');
        $championnat->setLogo('/images/logo/plLogo.png');
        $championnat->setNavbar('PremLeague');
        $championnat->setPayss($pays);
        $championnat->setApiId(2021);
        
        $manager->persist($championnat);

        //Creation des équipes
        $api = new FootballData();

        $equipes = $api->findTeamsCompetitionById(2021);

        try{
            foreach($equipes->teams as $equipe){
                $club = new Club();
                
                $club->setNom($equipe->name);
                $club->setLogo($equipe->crestUrl);
                $club->setNavbar($equipe->shortName);
                $club->setChampionnats($championnat);
                $club->setApiId($equipe->id);

                $manager->persist($club);
            }

            $manager->flush();

            $this->addFlash(
                'success',
                "Le championnat <strong>{$championnat->getNom()}</strong> a bien été enregistré !"
            );
        }catch(UniqueConstraintViolationException $ex){
            $this->addFlash(
                'danger',
                "Un ou des clubs du championnat ont déjà été importés !"
        );
        }

        return $this->redirectToRoute('admin_api_index');
        }
        else
        {
            $this->addFlash(
                'danger',
                "Un championnat possède déjà cet ID !"
            );

            return $this->redirectToRoute('admin_api_index');
        }
    }

    public function apiBundesliga(EntityManagerInterface $manager, PaysRepository $repoPays, ChampionnatRepository $repoChamp)
    {
        $champExiste = $repoChamp->findOneByApiId(2002);

        if($champExiste == null)
        {
        // Création du championnat
        $championnat = new Championnat();
        $pays = $repoPays->findOneByNomFr('Allemagne');

        $championnat->setNom('Bundesliga');
        $championnat->setLogo('/images/logo/bundesLogo.png');
        $championnat->setNavbar('Bundesliga');
        $championnat->setPayss($pays);
        $championnat->setApiId(2002);
        
        $manager->persist($championnat);

        //Creation des équipes
        $api = new FootballData();

        $equipes = $api->findTeamsCompetitionById(2002);
        try{
            foreach($equipes->teams as $equipe){
                $club = new Club();
                
                $club->setNom($equipe->name);
                $club->setLogo($equipe->crestUrl);
                $club->setNavbar($equipe->shortName);
                $club->setChampionnats($championnat);
                $club->setApiId($equipe->id);

                $manager->persist($club);
            }

            $manager->flush();

            $this->addFlash(
                'success',
                "Le championnat <strong>{$championnat->getNom()}</strong> a bien été enregistré !"
            );
        }catch(UniqueConstraintViolationException $ex){
            $this->addFlash(
                'danger',
                "Un ou des clubs du championnat ont déjà été importés !"
        );
        }

        return $this->redirectToRoute('admin_api_index');
        }
        else
        {
            $this->addFlash(
                'danger',
                "Un championnat possède déjà cet ID !"
            );

            return $this->redirectToRoute('admin_api_index');
        }
    }

    public function apiSerieA(EntityManagerInterface $manager, PaysRepository $repoPays, ChampionnatRepository $repoChamp)
    {
        $champExiste = $repoChamp->findOneByApiId(2019);

        if($champExiste == null)
        {
        // Création du championnat
        $championnat = new Championnat();
        $pays = $repoPays->findOneByNomFr('Italie');

        $championnat->setNom('Serie A');
        $championnat->setLogo('/images/logo/serieALogo.png');
        $championnat->setNavbar('Serie A');
        $championnat->setPayss($pays);
        $championnat->setApiId(2019);
        
        $manager->persist($championnat);

        //Creation des équipes
        $api = new FootballData();

        $equipes = $api->findTeamsCompetitionById(2019);
        try{
            foreach($equipes->teams as $equipe){
                $club = new Club();
                
                $club->setNom($equipe->name);
                $club->setLogo($equipe->crestUrl);
                $club->setNavbar($equipe->shortName);
                $club->setChampionnats($championnat);
                $club->setApiId($equipe->id);

                $manager->persist($club);
            }

            $manager->flush();

            $this->addFlash(
                'success',
                "Le championnat <strong>{$championnat->getNom()}</strong> a bien été enregistré !"
            );
        }catch(UniqueConstraintViolationException $ex){
            $this->addFlash(
                'danger',
                "Un ou des clubs du championnat ont déjà été importés !"
        );
        }

        return $this->redirectToRoute('admin_api_index');
        }
        else
        {
            $this->addFlash(
                'danger',
                "Un championnat possède déjà cet ID !"
            );

            return $this->redirectToRoute('admin_api_index');
        }
    }

    public function apiLigue1(EntityManagerInterface $manager, PaysRepository $repoPays, ChampionnatRepository $repoChamp)
    {
        $champExiste = $repoChamp->findOneByApiId(2015);

        if($champExiste == null)
        {
        // Création du championnat
        $championnat = new Championnat();
        $pays = $repoPays->findOneByNomFr('France');

        $championnat->setNom('Ligue 1');
        $championnat->setLogo('/images/logo/ligue1Logo.png');
        $championnat->setNavbar('Ligue 1');
        $championnat->setPayss($pays);
        $championnat->setApiId(2015);
        
        $manager->persist($championnat);

        //Creation des équipes
        $api = new FootballData();

        $equipes = $api->findTeamsCompetitionById(2015);
        
        try{
            foreach($equipes->teams as $equipe){
                $club = new Club();
                
                $club->setNom($equipe->name);
                $club->setLogo($equipe->crestUrl);
                $club->setNavbar($equipe->shortName);
                $club->setChampionnats($championnat);
                $club->setApiId($equipe->id);

                $manager->persist($club);
            }

            $manager->flush();

            $this->addFlash(
                'success',
                "Le championnat <strong>{$championnat->getNom()}</strong> a bien été enregistré !"
            );
        }catch(UniqueConstraintViolationException $ex){
            $this->addFlash(
                'danger',
                "Un ou des clubs du championnat ont déjà été importés !"
        );
        }

        return $this->redirectToRoute('admin_api_index');
        }
        else
        {
            $this->addFlash(
                'danger',
                "Un championnat possède déjà cet ID !"
            );

            return $this->redirectToRoute('admin_api_index');
        }
    }

    public function ligaClassement(EntityManagerInterface $manager, ClubRepository $repoClub)
    {
        $api = new FootballData();

        $classements = $api->findStandingsByCompetition(2014);

        try{
        foreach($classements->standings as $standing){
            if ($standing->type == 'TOTAL') { 
                foreach ($standing->table as $apiClass) {
                    $clubCourant = new Club();
                    $clubCourant = $repoClub->findOneByApiId($apiClass->team->id);

                    $classement = new Classement();
                    
                    $classement->setClubs($clubCourant);
                    $classement->setPlace($apiClass->position);
                    $classement->setTotal($apiClass->playedGames);
                    $classement->setVictoires($apiClass->won);
                    $classement->setNuls($apiClass->draw);
                    $classement->setDefaites($apiClass->lost);
                    $classement->setPoints($apiClass->points);
                    $classement->setButs($apiClass->goalsFor);
                    $classement->setEncaisse($apiClass->goalsAgainst);

                    $manager->persist($classement);
                }
            }
        }

        $manager->flush();

        $this->addFlash(
            'success',
            "Le classement a bien été enregistré !"
        );
        }catch(UniqueConstraintViolationException $ex){
            $this->addFlash(
                'danger',
                "Un ou des classements ont déjà été importé !"
        );
        }

        return $this->redirectToRoute('admin_api_index');
    }

    public function premierlClassement(EntityManagerInterface $manager, ClubRepository $repoClub)
    {
        $api = new FootballData();

        $classements = $api->findStandingsByCompetition(2021);
        try{
        foreach($classements->standings as $standing){
            if ($standing->type == 'TOTAL') { 
                foreach ($standing->table as $apiClass) {
                    $clubCourant = new Club();
                    $clubCourant = $repoClub->findOneByApiId($apiClass->team->id);

                    $classement = new Classement();
                    
                    $classement->setClubs($clubCourant);
                    $classement->setPlace($apiClass->position);
                    $classement->setTotal($apiClass->playedGames);
                    $classement->setVictoires($apiClass->won);
                    $classement->setNuls($apiClass->draw);
                    $classement->setDefaites($apiClass->lost);
                    $classement->setPoints($apiClass->points);
                    $classement->setButs($apiClass->goalsFor);
                    $classement->setEncaisse($apiClass->goalsAgainst);

                    $manager->persist($classement);
                }
            }
        }

        $manager->flush();

        $this->addFlash(
            'success',
            "Le classement a bien été enregistré !"
        );
        }catch(UniqueConstraintViolationException $ex){
            $this->addFlash(
                'danger',
                "Un ou des classements ont déjà été importé !"
        );
        }
        return $this->redirectToRoute('admin_api_index');
    }

    public function bundesligaClassement(EntityManagerInterface $manager, ClubRepository $repoClub)
    {
        $api = new FootballData();

        $classements = $api->findStandingsByCompetition(2002);

        try{
        foreach($classements->standings as $standing){
            if ($standing->type == 'TOTAL') { 
                foreach ($standing->table as $apiClass) {
                    $clubCourant = new Club();
                    $clubCourant = $repoClub->findOneByApiId($apiClass->team->id);

                    $classement = new Classement();
                    
                    $classement->setClubs($clubCourant);
                    $classement->setPlace($apiClass->position);
                    $classement->setTotal($apiClass->playedGames);
                    $classement->setVictoires($apiClass->won);
                    $classement->setNuls($apiClass->draw);
                    $classement->setDefaites($apiClass->lost);
                    $classement->setPoints($apiClass->points);
                    $classement->setButs($apiClass->goalsFor);
                    $classement->setEncaisse($apiClass->goalsAgainst);

                    $manager->persist($classement);
                }
            }
        }

        $manager->flush();

        $this->addFlash(
            'success',
            "Le classement a bien été enregistré !"
        );
        }catch(UniqueConstraintViolationException $ex){
            $this->addFlash(
                'danger',
                "Un ou des classements ont déjà été importé !"
        );
        }
        return $this->redirectToRoute('admin_api_index');
    }

    public function serieAClassement(EntityManagerInterface $manager, ClubRepository $repoClub)
    {
        $api = new FootballData();

        $classements = $api->findStandingsByCompetition(2019);

        try{
        foreach($classements->standings as $standing){
            if ($standing->type == 'TOTAL') { 
                foreach ($standing->table as $apiClass) {
                    $clubCourant = new Club();
                    $clubCourant = $repoClub->findOneByApiId($apiClass->team->id);

                    $classement = new Classement();
                    
                    $classement->setClubs($clubCourant);
                    $classement->setPlace($apiClass->position);
                    $classement->setTotal($apiClass->playedGames);
                    $classement->setVictoires($apiClass->won);
                    $classement->setNuls($apiClass->draw);
                    $classement->setDefaites($apiClass->lost);
                    $classement->setPoints($apiClass->points);
                    $classement->setButs($apiClass->goalsFor);
                    $classement->setEncaisse($apiClass->goalsAgainst);

                    $manager->persist($classement);
                }
            }
        }

        $manager->flush();

        $this->addFlash(
            'success',
            "Le classement a bien été enregistré !"
        );
        }catch(UniqueConstraintViolationException $ex){
            $this->addFlash(
                'danger',
                "Un ou des classements ont déjà été importé !"
        );
        }
        return $this->redirectToRoute('admin_api_index');
    }

    public function ligue1Classement(EntityManagerInterface $manager, ClubRepository $repoClub)
    {
        $api = new FootballData();

        $classements = $api->findStandingsByCompetition(2015);
        
        try{
        foreach($classements->standings as $standing){
            if ($standing->type == 'TOTAL') { 
                foreach ($standing->table as $apiClass) {
                    $clubCourant = new Club();
                    $clubCourant = $repoClub->findOneByApiId($apiClass->team->id);

                    $classement = new Classement();
                    
                    $classement->setClubs($clubCourant);
                    $classement->setPlace($apiClass->position);
                    $classement->setTotal($apiClass->playedGames);
                    $classement->setVictoires($apiClass->won);
                    $classement->setNuls($apiClass->draw);
                    $classement->setDefaites($apiClass->lost);
                    $classement->setPoints($apiClass->points);
                    $classement->setButs($apiClass->goalsFor);
                    $classement->setEncaisse($apiClass->goalsAgainst);

                    $manager->persist($classement);
                }
            }
        }

        $manager->flush();

        $this->addFlash(
            'success',
            "Le classement a bien été enregistré !"
        );
        }catch(UniqueConstraintViolationException $ex){
            $this->addFlash(
                'danger',
                "Un ou des classements ont déjà été importé !"
        );
        }

        return $this->redirectToRoute('admin_api_index');
    }

    public function ligaJoueurs(EntityManagerInterface $manager, ClubRepository $repoClub, ChampionnatRepository $repoChamp)
    {
        $championnat = $repoChamp->findOneByApiId(2014);
        $clubs = $repoClub->findByChampionnats($championnat);
        
        foreach($clubs as $club)
        {
            $api = new FootballData();

            $clubApi = $api->findTeamById($club->getApiId());

            foreach($clubApi->squad as $joueurs)
            {
                $joueur = new Joueur();

                $joueur->setNom($joueurs->name);
                $joueur->setApiId($joueurs->id);

                $manager->persist($joueur);
            }

            $manager->persist($club);
        }

        $manager->flush();

        $this->addFlash(
            'success',
            "Les joueurs ont bien été enregistrés !"
        );

        return $this->redirectToRoute('admin_api_index');
    }

    public function rencontreLiga(EntityManagerInterface $manager, ChampionnatRepository $repoChamp, ClubRepository $repoClub, RencontreRepository $repoRenc)
    {
        $api = new FootballData();
        $championnat = $repoChamp->findOneByApiId(2014);

        $matches = $api->findMatchsCompetitionById(2014);
    
        $rencTest = $repoRenc->findByChampionnats($championnat);

        if($rencTest == null)
        {
        foreach($matches->matches as $match)
        {
            $rencontre = new Rencontre();

            $equipeDomicile = $repoClub->findOneByApiId($match->homeTeam->id);
            $equipeExterieur = $repoClub->findOneByApiId($match->awayTeam->id);

            $rencontre->setChampionnats($championnat);
            $rencontre->setClubDom($equipeDomicile);
            $rencontre->setClubExt($equipeExterieur);
            $rencontre->setMatchDate(new DateTime($match->utcDate));

            $manager->persist($rencontre);
        }

        $manager->flush();

        $this->addFlash(
            'success',
            "Les rencontres ont bien été enregistrées !"
        );
        
        return $this->redirectToRoute('admin_api_index');
        }
        else
        {
            $this->addFlash(
                'danger',
                "Des rencontres du championnat ont déjà été enregistrées !"
            );
            
            return $this->redirectToRoute('admin_api_index');
        }
    }

    public function rencontrePremierl(EntityManagerInterface $manager, ChampionnatRepository $repoChamp, ClubRepository $repoClub, RencontreRepository $repoRenc)
    {
        $api = new FootballData();
        $championnat = $repoChamp->findOneByApiId(2021);

        $matches = $api->findMatchsCompetitionById(2021);
        
        $rencTest = $repoRenc->findByChampionnats($championnat);

        if($rencTest == null)
        {
        foreach($matches->matches as $match)
        {
            $rencontre = new Rencontre();

            $equipeDomicile = $repoClub->findOneByApiId($match->homeTeam->id);
            $equipeExterieur = $repoClub->findOneByApiId($match->awayTeam->id);

            $rencontre->setChampionnats($championnat);
            $rencontre->setClubDom($equipeDomicile);
            $rencontre->setClubExt($equipeExterieur);
            $rencontre->setMatchDate(new DateTime($match->utcDate));

            $manager->persist($rencontre);
        }

        $manager->flush();

        $this->addFlash(
            'success',
            "Les rencontres ont bien été enregistrées !"
        );

        return $this->redirectToRoute('admin_api_index');
        }
        else
        {
            $this->addFlash(
                'danger',
                "Des rencontres du championnat ont déjà été enregistrées !"
            );
            
            return $this->redirectToRoute('admin_api_index');
        }
    }

    public function rencontreBundesliga(EntityManagerInterface $manager, ChampionnatRepository $repoChamp, ClubRepository $repoClub, RencontreRepository $repoRenc)
    {
        $api = new FootballData();
        $championnat = $repoChamp->findOneByApiId(2002);

        $matches = $api->findMatchsCompetitionById(2002);
        
        $rencTest = $repoRenc->findByChampionnats($championnat);

        if($rencTest == null)
        {
        foreach($matches->matches as $match)
        {
            $rencontre = new Rencontre();

            $equipeDomicile = $repoClub->findOneByApiId($match->homeTeam->id);
            $equipeExterieur = $repoClub->findOneByApiId($match->awayTeam->id);

            $rencontre->setChampionnats($championnat);
            $rencontre->setClubDom($equipeDomicile);
            $rencontre->setClubExt($equipeExterieur);
            $rencontre->setMatchDate(new DateTime($match->utcDate));

            $manager->persist($rencontre);
        }

        $manager->flush();

        $this->addFlash(
            'success',
            "Les rencontres ont bien été enregistrées !"
        );
        
        return $this->redirectToRoute('admin_api_index');
        }
        else
        {
            $this->addFlash(
                'danger',
                "Des rencontres du championnat ont déjà été enregistrées !"
            );
            
            return $this->redirectToRoute('admin_api_index');
        }
    }

    public function rencontreSeriea(EntityManagerInterface $manager, ChampionnatRepository $repoChamp, ClubRepository $repoClub, RencontreRepository $repoRenc)
    {
        $api = new FootballData();
        $championnat = $repoChamp->findOneByApiId(2019);

        $matches = $api->findMatchsCompetitionById(2019);
        
        $rencTest = $repoRenc->findByChampionnats($championnat);

        if($rencTest == null)
        {
        foreach($matches->matches as $match)
        {
            $rencontre = new Rencontre();

            $equipeDomicile = $repoClub->findOneByApiId($match->homeTeam->id);
            $equipeExterieur = $repoClub->findOneByApiId($match->awayTeam->id);

            $rencontre->setChampionnats($championnat);
            $rencontre->setClubDom($equipeDomicile);
            $rencontre->setClubExt($equipeExterieur);
            $rencontre->setMatchDate(new DateTime($match->utcDate));

            $manager->persist($rencontre);
        }

        $manager->flush();

        $this->addFlash(
            'success',
            "Les rencontres ont bien été enregistrées !"
        );
        
        return $this->redirectToRoute('admin_api_index');
        }
        else
        {
            $this->addFlash(
                'danger',
                "Des rencontres du championnat ont déjà été enregistrées !"
            );
            
            return $this->redirectToRoute('admin_api_index');
        }
    }

    public function rencontreLigue1(EntityManagerInterface $manager, ChampionnatRepository $repoChamp, ClubRepository $repoClub, RencontreRepository $repoRenc)
    {
        $api = new FootballData();
        $championnat = $repoChamp->findOneByApiId(2015);

        $matches = $api->findMatchsCompetitionById(2015);
        
        $rencTest = $repoRenc->findByChampionnats($championnat);

        if($rencTest == null)
        {
        foreach($matches->matches as $match)
        {
            $rencontre = new Rencontre();

            $equipeDomicile = $repoClub->findOneByApiId($match->homeTeam->id);
            $equipeExterieur = $repoClub->findOneByApiId($match->awayTeam->id);

            $rencontre->setChampionnats($championnat);
            $rencontre->setClubDom($equipeDomicile);
            $rencontre->setClubExt($equipeExterieur);
            $rencontre->setMatchDate(new DateTime($match->utcDate));

            $manager->persist($rencontre);
        }

        $manager->flush();

        $this->addFlash(
            'success',
            "Les rencontres ont bien été enregistrées !"
        );
        
        return $this->redirectToRoute('admin_api_index');
    }
    else
    {
        $this->addFlash(
            'danger',
            "Des rencontres du championnat ont déjà été enregistrées !"
        );
        
        return $this->redirectToRoute('admin_api_index');
    }
    }
}
    




