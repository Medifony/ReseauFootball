<?php

namespace App\Controller;

use DateTime;
use App\Entity\Sujet;
use App\Entity\Reponse;
use App\Form\AdminReponseType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SignalementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminReponseController extends AbstractController
{
    public function create(EntityManagerInterface $manager, Request $request) 
    {
        $reponse = new Reponse();

        $form = $this->createForm(AdminReponseType::class, $reponse);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){ 
            $reponse->getSujets()->setDateModif(new DateTime('now'));
            
            $manager->persist($reponse->getSujets());
            $manager->persist($reponse);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réponse <strong>{$reponse->getId()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('admin_reponses_create');
        }

        return $this->render('admin/reponse/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit(Reponse $reponse, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(AdminReponseType::class, $reponse);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($reponse);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réponse <strong>{$reponse->getId()}</strong> a bien été enregistrée"
            );
        }

        return $this->render('admin/reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView()
        ]);
    }

    public function delete(Reponse $reponse, EntityManagerInterface $manager, SignalementRepository $repoSignal){
        $reponse->setSujets(new Sujet());

        $signalements = $repoSignal->findByReponses($reponse);
        
        foreach($signalements as $signalement)
        {
           $manager->remove($signalement);
        }

        $manager->remove($reponse);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réponse <strong>{$reponse->getId()}</strong> a bien été supprimée !"
        );
        
        return $this->redirectToRoute('admin_reponses_index');
    }
}
