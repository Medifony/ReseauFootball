<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\User;
use App\Entity\Sujet;
use App\Entity\Championnat;
use App\Form\AdminSujetType;
use App\Repository\SujetRepository;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SignalementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminSujetController extends AbstractController
{
    public function create(EntityManagerInterface $manager, Request $request) 
    {
        $sujet = new Sujet();

        $form = $this->createForm(AdminSujetType::class, $sujet);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){ 
            if($sujet->getClubs() == null OR ($sujet->getClubs()->getChampionnats() == $sujet->getChampionnats()))
            {
                $manager->persist($sujet);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Le sujet <strong>{$sujet->getTitre()}</strong> a bien été enregistré !"
                );

                return $this->redirectToRoute('admin_sujets_create');
        
            }
            else
            {
                $this->addFlash(
                    'danger',
                    "Le club <strong>{$sujet->getClubs()->getNom()}</strong> ne fait pas partie du Championnat <strong>{$sujet->getChampionnats()->getNom()}</strong>!"
                );

                return $this->redirectToRoute('admin_sujets_create');
            }
        }
        return $this->render('admin/sujet/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit(Sujet $sujet, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(AdminSujetType::class, $sujet);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if($sujet->getClubs() == null OR ($sujet->getClubs()->getChampionnats() == $sujet->getChampionnats()))
            {
                $manager->persist($sujet);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Le sujet <strong>{$sujet->getTitre()}</strong> a bien été enregistré"
                );
            }
            else
            {
                $this->addFlash(
                    'danger',
                    "Le club <strong>{$sujet->getClubs()->getNom()}</strong> ne fait pas partie du Championnat <strong>{$sujet->getChampionnats()->getNom()}</strong>!"
                );
            }
        }

        return $this->render('admin/sujet/edit.html.twig', [
            'sujet' => $sujet,
            'form' => $form->createView()
        ]);
    }

    public function delete(Sujet $sujet, EntityManagerInterface $manager, ReponseRepository $repoReponse, SignalementRepository $repoSignal){
        $reponses = $repoReponse->findBySujets($sujet);
        $signalements = $repoSignal->findBySujets($sujet);

        foreach($reponses as $reponse)
        {
            $manager->remove($reponse);
        }

        foreach($signalements as $signalement)
        {
            $manager->remove($signalement);
        }

        $sujet->setUsers(new User());
        $sujet->setChampionnats(new Championnat());
        $sujet->setClubs(new Club());
        
        $manager->remove($sujet);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le sujet <strong>{$sujet->getTitre()}</strong> a bien été supprimé !"
        );
        
        return $this->redirectToRoute('admin_sujets_index');
    }
}
