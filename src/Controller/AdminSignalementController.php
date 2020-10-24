<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Sujet;
use App\Entity\Reponse;
use App\Entity\Signalement;
use App\Form\AdminSignalementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminSignalementController extends AbstractController
{

    public function create(EntityManagerInterface $manager, Request $request) 
    {
        $signalement = new Signalement();

        $form = $this->createForm(AdminSignalementType::class, $signalement);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){ 
            if($signalement->getReponses() == null && $signalement->getSujets() == null)
            {
                $this->addFlash(
                    'danger',
                    "Le signalement doit concerner un sujet OU une réponse !"
                );

                return $this->redirectToRoute('admin_signalements_create');
            }
            elseif($signalement->getReponses() != null && $signalement->getSujets() != null )
            {
                $this->addFlash(
                    'danger',
                    "Le signalement doit concerner un sujet OU une réponse !"
                );

                return $this->redirectToRoute('admin_signalements_create');
            }
            else
            {
                $manager->persist($signalement);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Le signalement <strong>{$signalement->getId()}</strong> a bien été enregistré !"
                );

                return $this->redirectToRoute('admin_signalements_create');
            }
        }

        return $this->render('admin/signalement/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit(Signalement $signalement, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(AdminSignalementType::class, $signalement);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($signalement);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le signalement <strong>{$signalement->getId()}</strong> a bien été enregistré"
            );
        }

        return $this->render('admin/signalement/edit.html.twig', [
            'signalement' => $signalement,
            'form' => $form->createView()
        ]);
    }

    public function delete(Signalement $signalement, EntityManagerInterface $manager)
    {
            $signalement->setUsers(new User());
            $signalement->setReponses(new Reponse());
            $signalement->setSujets(new Sujet());

            $manager->remove($signalement);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le signalement <strong>{$signalement->getId()}</strong> a bien été supprimée !"
            );
            
        return $this->redirectToRoute('admin_signalements_index');
    }
}
