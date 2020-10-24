<?php

namespace App\Controller;

use App\Entity\Pays;
use App\Entity\Championnat;
use App\Form\ChampionnatType;
use App\Repository\ClubRepository;
use App\Repository\SujetRepository;
use App\Repository\RencontreRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ChampionnatRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminChampionnatController extends AbstractController
{
    public function create(EntityManagerInterface $manager, Request $request) 
    {
        $championnat = new Championnat();

        $form = $this->createForm(ChampionnatType::class, $championnat);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){ 
            $manager->persist($championnat);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le championnat <strong>{$championnat->getNom()}</strong> a bien été enregistré !"
            );

            return $this->redirectToRoute('admin_championnats_create');
        }

        return $this->render('admin/championnat/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit(Championnat $championnat, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(ChampionnatType::class, $championnat);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($championnat);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le championnat <strong>{$championnat->getNom()}</strong> a bien été enregistré"
            );
        }

        return $this->render('admin/championnat/edit.html.twig', [
            'champ' => $championnat,
            'form' => $form->createView()
        ]);
    }

    public function delete(Championnat $championnat, EntityManagerInterface $manager, ClubRepository $repoClub, SujetRepository $repoSujet, RencontreRepository $repoRencontre)
    {

            $clubs = $repoClub->findByChampionnats($championnat);
            $sujets = $repoSujet->findByChampionnats($championnat);
            $rencontres = $repoRencontre->findByChampionnats($championnat);

            foreach($clubs as $club)
            {
                $club->setChampionnats(null);
            }
            foreach($sujets as $sujet)
            {
                $sujet->setChampionnats(null);
            }
            foreach($rencontres as $rencontre)
            {
                $manager->remove($rencontre);
            }

            $championnat->setPayss(new Pays());

            $manager->remove($championnat);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le championnat <strong>{$championnat->getNom()}</strong> a bien été supprimé !"
            );
            
        return $this->redirectToRoute('admin_championnats_index');
    }
}
