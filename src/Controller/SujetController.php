<?php

namespace App\Controller;

use DateTime;
use App\Entity\Sujet;
use App\Entity\Reponse;
use App\Form\SujetType;
use App\Form\ReponseType;
use App\Repository\ClubRepository;
use App\Repository\SujetRepository;
use App\Repository\ReponseRepository;
use App\Repository\DemandeAmiRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ChampionnatRepository;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SujetController extends AbstractController
{
    public function indexChamp(Request $request, $slugCh, $slugSuj, ChampionnatRepository $repoChamp, SujetRepository $repoSuj, 
                                EntityManagerInterface $manager, ReponseRepository $repoRep, DemandeAmiRepository $repoDemande)
    {
        $reponse = new Reponse();

        $champ = $repoChamp->findOneBySlug($slugCh);

        $sujet = $repoSuj->findBy(array('slug' => $slugSuj, 'championnats' => $champ, 'clubs' => null));

        $listeRep = $repoRep->findBy(array('sujets' => $sujet), array('repDate' => 'asc',));

        if($this->getUser() != null){
            $checkAmis = $repoDemande->findListeAmis($this->getUser()->getSlug());
        }
        else
        {
            $checkAmis = 'aucun';
        }

        $form = $this->createForm(ReponseType::class, $reponse);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $user = $this->getUser();

            $reponse->setUsers($user);
            $reponse->setSujets($sujet[0]);

            $sujet[0]->setDateModif(new DateTime('now'));

            $manager->persist($sujet[0]);
            $manager->persist($reponse);
            $manager->flush();

            return $this->redirectToRoute('sujet_champ_page', array('slugCh' => $champ->getSlug(),
                                                              'slugSuj' => $sujet[0]->getSlug(), ));
        }

        return $this->render('sujet/index.html.twig', [
            'champ' => $champ,
            'sujet' => $sujet[0],
            'reponses' => $listeRep,
            'checkAmis' => $checkAmis,
            'form' => $form->createView()     
        ]);
    }

    public function indexClub(Request $request, $slugCh, $slugCl, $slugSuj, ChampionnatRepository $repoChamp, SujetRepository $repoSuj, 
                            EntityManagerInterface $manager, ReponseRepository $repoRep, ClubRepository $repoClub, DemandeAmiRepository $repoDemande)
    {
        $reponse = new Reponse();

        $champ = $repoChamp->findOneBySlug($slugCh);

        $club = $repoClub->findOneBySlug($slugCl);

        $sujet = $repoSuj->findBy(array('slug' => $slugSuj, 'championnats' => $champ, 'clubs' => $club));

        $listeRep = $repoRep->findBy(array('sujets' => $sujet), array('repDate' => 'asc',));

        if($this->getUser() != null){
            $checkAmis = $repoDemande->findListeAmis($this->getUser()->getSlug());
        }
        else
        {
            $checkAmis = 'aucun';
        }

        $form = $this->createForm(ReponseType::class, $reponse);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $reponse->setUsers($user);
            $reponse->setSujets($sujet[0]);

            $sujet[0]->setDateModif(new DateTime('now'));

            $manager->persist($sujet[0]);
            $manager->persist($reponse);
            $manager->flush();

            return $this->redirectToRoute('sujet_club_page', array('slugCh' => $champ->getSlug(),
                                                              'slugCl' => $club->getSlug(),
                                                              'slugSuj' => $sujet[0]->getSlug(), ));
        }

        return $this->render('sujet/index.html.twig', [
            'champ' => $champ,
            'sujet' => $sujet[0],
            'reponses' => $listeRep,
            'club' => $club,
            'checkAmis' => $checkAmis,
            'form' => $form->createView()     
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */

    public function creerChamp(Request $request, $slug, ChampionnatRepository $repoChamp, EntityManagerInterface $manager)
    {
        $sujet = new Sujet();
        $champ = $repoChamp->findOneBySlug($slug);

        $form = $this->createForm(SujetType::class, $sujet);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $sujet->setUsers($user);
            $sujet->setChampionnats($champ);

            $manager->persist($sujet);
            $manager->flush();

            return $this->redirectToRoute('champ_page', array('slug' => $champ->getSlug(), ));
        }

        return $this->render('sujet/creer.html.twig', [
            'champ' => $champ,
            'form' => $form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    
    public function creerClub(Request $request, $slugCh, $slugCl, ClubRepository $repoClub, ChampionnatRepository $repoChamp, EntityManagerInterface $manager)
    {
        $sujet = new Sujet();
        $champ = $repoChamp->findOneBySlug($slugCh);

        $club = $repoClub->findBy(array('slug' => $slugCl,
                                 'championnats' => $champ));

        $form = $this->createForm(SujetType::class, $sujet);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $sujet->setUsers($user);
            $sujet->setChampionnats($champ);

            foreach($club as $cl){
                $sujet->setClubs($cl);
            }

            $manager->persist($sujet);
            $manager->flush();

            return $this->redirectToRoute('club_page', array('slugCh' => $champ->getSlug(),
                                                              'slugCl' => $club[0]->getSlug(), ));
        }


        return $this->render('sujet/creer.html.twig', [
            'clubs' => $club,
            'champ' => $champ,
            'form' => $form->createView()
        ]);
    }
}
